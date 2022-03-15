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
use DB;

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
                    'value' => $key->value,
                    'public' => $key->public,
                    'edit_url' => route('key.show', $key),
                ];
            }),
            'sharedKeys' => $this->allowedSharedKeys()->map(function ($key) {
                return [
                    'user_id' => $key->user_id,
                    'owner_id' => $key->owner_id,
                    'description' => $key->description,
                    'value' => $key->value,
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
        return Inertia::render('Keys/Show', [
            'skey' => $key->load('user'),
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
            'owner_id' => ['required'],
            'shared_email' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:100'],
            'value' => ['required', 'string', 'max:50'],
            'public' => ['required', 'boolean'],
        ], $this->rules(), [
            'shared_email.exists' => __('We were unable to find a registered user with this email address.'),
        ])->validateWithBag('shareKey');

        $sharedKey = SharedKey::create($input);

        return redirect()->route('key.show', ['key' => $sharedKey->key_id]);
    }

    /**
     * Get the validation rules for adding a shared key.
     *
     * @return array
     */
    protected function rules()
    {
        return array_filter([
            'shared_email' => ['required', 'email', 'exists:users'],
        ]);
    }

    /**
     * Remove a resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Key $key)
    {
        $input = $request->all();

        Validator::make($input, [
            'shared_email' => ['required', 'string', 'max:100'],
        ])->validateWithBag('revokeKey');
        
        SharedKey::select('*')
            ->where('key_id', '=', $key->id)
            ->where('shared_email', '=', $input)
            ->firstorfail()
            ->delete();

        return redirect()->route('key.show', ['key' => $key->id]);
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

        return SharedKey::select('*')
            ->where('shared_email', '=', $user->email)
            ->get();
    }
}