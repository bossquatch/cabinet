<?php

namespace App\Http\Controllers;

use App\Models\Key;
use App\Models\SharedKey;
use App\Models\User;
use App\Models\Team;
use App\Models\KeyAccessRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class KeyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->adminKeyAccess();

        return Inertia::render('Keys/Index', [
            'keys' => $this->allowedKeys()->map(function ($key) {
                return [
                    'user_id' => $key->user_id,
                    'owner_id' => $key->owner_id,
                    'description' => $key->description,
                    'value' => Crypt::decryptString($key->value),
                    'public' => $key->public,
                    'edit_url' => route('key.show', $key),
                ];
            }),
            'sharedKeys' => $this->allowedSharedKeys()->map(function ($key) {
                return [
                    'user_id' => $key->user_id,
                    'owner_id' => $key->owner_id,
                    'description' => $key->description,
                    'value' => Crypt::decryptString($key->value),
                    'public' => $key->public,
                    'edit_url' => route('key.show', $key),
                ];
            }),
            'adminAccessedKeys' => $this->allowedAdminAccessedKeys()->map(function ($key) {
                return [
                    'user_id' => $key->user_id,
                    'owner_id' => $key->owner_id,
                    'description' => $key->description,
                    'value' => Crypt::decryptString($key->value),
                    'public' => $key->public,
                    'edit_url' => route('key.show', $key),
                ];
            })
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Keys/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        Validator::make($input, [
            'user_id' => ['required'],
            'owner_id' => ['required'],
            'description' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:512'],
            'public' => ['required', 'boolean'],
        ])->validateWithBag('createKey');

        $input['value'] = Crypt::encryptString($input['value']);

        $key = Key::create($input);

        return redirect()->route('key.show', ['key' => $key->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Key  $key
     * @return \Illuminate\Http\Response
     */
    public function show(Key $key)
    {   
        $shared_keys = SharedKey::where('key_id', '=', $key->id)->get();
        $shared_users = User::whereIn('email', $shared_keys->map(function ($k) { return ['email' => $k->shared_email]; }))->get();
        
        try {
            $key->value = Crypt::decryptString($key->value);
        } catch (DecryptException $e) {
            //
        }

        return Inertia::render('Keys/Show', [
            'skey' => $key->load('user'),
            'shared_users' => $shared_users,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Key  $key
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Key $key)
    {
        $input = $request->all();

        Validator::make($input, [
            'description' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:512'],
            'public' => ['required', 'boolean'],
        ])->validateWithBag('updateKey');
        
        $input['value'] = Crypt::encryptString($input['value']);

        $key->update($input);

        if ($input['public'])
        {
            SharedKey::select('*')
                ->where('key_id', '=', $key->id)
                ->delete();
        }

        return back(303);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function userShare(Request $request)
    {
        $input = $request->all();
        
        Validator::make($input, [
            'key_id' => ['required'],
            'shared_email' => ['required', 'string', 'max:255'],
        ])->after(
            $this->ensureEmailExists($input)
        )->after(
            $this->ensureEmailNotCurrentUser($input)
        )->after(
            $this->ensureKeyNotAlreadyShared($input)
        )->validateWithBag('shareUserKey');

        $sharedKey = SharedKey::create($input);

        return redirect()->route('key.show', ['key' => $sharedKey->key_id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \App\Models\Team $team
     * @return \Illuminate\Http\Response
     */
    public function teamShare(Request $request, Team $team)
    {
        $input = $request->all();
        
        foreach ($team->users as $user)
        {
            $userInfo = User::where('id', $user->id)->firstorfail();
            $input['shared_email'] = $userInfo->email;
            
            $alreadyShared = SharedKey::select('*')
                ->where('key_id', '=', $input['key_id'])
                ->where('shared_email', '=', $input['shared_email'])
                ->exists();

            if (!$alreadyShared)
            {
                Validator::make($input, [
                    'key_id' => ['required'],
                    'shared_email' => ['required', 'string', 'max:255'],
                ])->after(
                    $this->ensureKeyNotAlreadyShared($input)
                )->validateWithBag('shareTeamKey');
        
                SharedKey::create($input);
            }
        }

        return redirect()->route('key.show', ['key' => $input['key_id']]);
    }

    /**
     * Remove a resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \App\Models\Key $key
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function revoke(Request $request, Key $key, User $user)
    {   
        SharedKey::select('*')
            ->where('key_id', '=', $key->id)
            ->where('shared_email', '=', $user->email)
            ->firstorfail()
            ->delete();

        return redirect()->route('key.show', ['key' => $key->id]);
    }

    /**
     * Remove a resource in storage.
     *
     * @param \App\Models\Key $key
     * @return \Illuminate\Http\Response
     */
    public function delete(Key $key)
    {   
        Key::select('*')
            ->where('id', '=', $key->id)
            ->firstorfail()
            ->delete();

        SharedKey::select('*')
            ->where('key_id', '=', $key->id)
            ->delete();

        return redirect()->route('key.index');
    }

    /**
     * Get the allowed personal and public keys for the user.
     *
     * @return array
     */
    private function allowedKeys()
    {
        $user = auth()->user();
        
        return Key::where('user_id', $user->id)->orWhere('public', true)->get();
    }

    /**
     * Get the allowed shared keys for the user.
     *
     * @return array
     */
    private function allowedSharedKeys()
    {
        $user = auth()->user();

        $keys = SharedKey::select('*')->where('shared_email', '=', $user->email)->get();

        return Key::whereIn('id', $keys->map(function ($key) { return ['id' => $key->key_id]; })->pluck('id'))->get();
    }

    /**
     * Get the admin allowed accessed keys for requested users.
     *
     * @return array
     */
    private function allowedAdminAccessedKeys()
    {
        $user = auth()->user();
        $requests = KeyAccessRequest::where('admin_id', $user->id)->where('approved', true)->get();
        $users = User::whereIn('email', $requests->map(function ($request) { return ['user_email' => $request->user_email]; })->pluck('user_email'))->get();
        
        return Key::whereIn('owner_id', $users->map(function ($user) { return ['id' => $user->id]; })->pluck('id'))->get();
    }

    /**
     * Check if admin access to a user's keys is still available.
     */
    private function adminKeyAccess()
    {
        $requests = KeyAccessRequest::where('approved', true)->get();

        foreach ($requests as $request)
        {
            $approvedTime = Carbon::createFromFormat('Y-m-d H:s:i', KeyAccessRequest::where('id', $request->id)->pluck('approved_at')->first());
            $currentTime = Carbon::now();
            $diff_in_hours = $approvedTime->diffInHours($currentTime);

            if ($diff_in_hours > 24)
            {
                KeyAccessRequest::select('*')
                    ->where('id', '=', $request->id)
                    ->firstorfail()
                    ->delete();
            }
        }

        return;
    }

    /**
     * Ensure that the email exists.
     *
     * @param  mixed  $input
     * @return \Closure
     */
    protected function ensureEmailExists(mixed $input)
    {
        $email = User::select('*')
            ->where('email', '=', $input['shared_email'])
            ->exists();

        return function ($validator) use ($email) {
            $validator->errors()->addIf(
                !$email,
                'shared_email',
                __('We were unable to find a registered user with this email address.')
            );
        };
    }

    /**
     * Ensure that the email is not the current user.
     *
     * @param  mixed  $input
     * @return \Closure
     */
    protected function ensureEmailNotCurrentUser(mixed $input)
    {
        $user = auth()->user();
        $myEmail = false;

        if ($input['shared_email'] == $user->email)
        {
            $myEmail = true;
        }

        return function ($validator) use ($myEmail) {
            $validator->errors()->addIf(
                $myEmail,
                'shared_email',
                __('You already have access to this key.')
            );
        };
    }

    /**
     * Ensure that the key is not already being shared with the user.
     *
     * @param  mixed  $input
     * @return \Closure
     */
    protected function ensureKeyNotAlreadyShared(mixed $input)
    {
        $shared_key = SharedKey::select('*')
            ->where('key_id', '=', $input['key_id'])
            ->where('shared_email', '=', $input['shared_email'])
            ->exists();

        return function ($validator) use ($shared_key) {
            $validator->errors()->addIf(
                $shared_key,
                'shared_email',
                __('This user already shares this key.')
            );
        };
    }
}