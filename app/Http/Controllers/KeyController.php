<?php

namespace App\Http\Controllers;

use App\Models\Key;
use App\Models\SharedKey;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Crypt;

class KeyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

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
            'myID' => $user->id,
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
            'description' => ['required', 'string', 'max:100'],
            'value' => ['required', 'string', 'max:50'],
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
            'description' => ['required', 'string', 'max:100'],
            'value' => ['required', 'string', 'max:50'],
            'public' => ['required', 'boolean'],
        ])->validateWithBag('updateKey');
        
        $input['value'] = Crypt::encryptString($input['value']);

        $key->update($input);

        return back(303);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function share(Request $request)
    {
        $input = $request->all();

        Validator::make($input, [
            'key_id' => ['required'],
            'shared_email' => ['required', 'string', 'max:100'],
        ])->after(
            $this->ensureEmailExists($input)
        )->after(
            $this->ensureEmailNotCurrentUser($input)
        )->after(
            $this->ensureKeyNotAlreadyShared($input)
        )->validateWithBag('shareKey');

        $sharedKey = SharedKey::create($input);

        return redirect()->route('key.show', ['key' => $sharedKey->key_id]);
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
        $currentUser = auth()->user();

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

        return Key::select('*')
            ->where('public', '=', true)
            ->orWhere('user_id', '=', $user->id)
            ->get();
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

    /**
     * Ensure that the key exists.
     *
     * @param  mixed  $input
     * @return \Closure
     */
    protected function ensureKeyExists(mixed $input)
    {
        $shared_key = SharedKey::select('*')
            ->where('key_id', '=', $input['key_id'])
            ->where('shared_email', '=', $input['shared_email'])
            ->exists();

        return function ($validator) use ($shared_key) {
            $validator->errors()->addIf(
                !$shared_key,
                'shared_email',
                __('This key is not shared with this user.')
            );
        };
    }
}