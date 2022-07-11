<?php

namespace App\Http\Controllers;

use App\Models\Key;
use App\Models\SharedKey;
use App\Models\User;
use App\Models\Team;
use App\Models\KeyAccessRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\KeyAccessRequestController;

class KeyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        (new KeyAccessRequestController)->adminKeyAccess();

        return Inertia::render('Keys/Index', [
            'categories' => $this->categories()->map(function ($cat) {
                return [
                    'name' => $cat->name,
                    'keys' => $this->categoryKeys($cat->name)->map(function ($key) {
                        return [
                            'id' => $key->id,
                            'user_id' => $key->user_id,
                            'owner_id' => $key->owner_id,
                            'description' => $key->description,
                            'value' => Crypt::decryptString($key->value),
                            'public' => $key->public,
                            'is_hidden' => $key->is_hidden,
                            'edit_url' => route('key.show', $key)
                        ];
                    })
                ];
            }),
            'categoryKeys' => $this->allCategoryKeys()->map(function ($key) {
                return [
                    'id' => $key->id,
                    'user_id' => $key->user_id,
                    'owner_id' => $key->owner_id,
                    'description' => $key->description,
                    'value' => Crypt::decryptString($key->value),
                    'public' => $key->public,
                    'is_hidden' => $key->is_hidden,
                    'edit_url' => route('key.show', $key)
                ];
            }),
            'keys' => $this->allowedKeys()->map(function ($key) {
                return [
                    'id' => $key->id,
                    'user_id' => $key->user_id,
                    'owner_id' => $key->owner_id,
                    'description' => $key->description,
                    'value' => Crypt::decryptString($key->value),
                    'public' => $key->public,
                    'is_hidden' => $key->is_hidden,
                    'edit_url' => route('key.show', $key)
                ];
            }),
            'adminAccessedKeys' => $this->allowedAdminAccessedKeys()->map(function ($key) {
                return [
                    'id' => $key->id,
                    'user_id' => $key->user_id,
                    'owner_id' => $key->owner_id,
                    'description' => $key->description,
                    'value' => Crypt::decryptString($key->value),
                    'public' => $key->public,
                    'is_hidden' => $key->is_hidden,
                    'edit_url' => route('key.show', $key)
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
        $user = auth()->user();
        $shared_keys = SharedKey::where('key_id', '=', $key->id)->get();
        $shared_users = User::whereIn('email', $shared_keys->map(function ($k) { return ['email' => $k->shared_email]; }))->get();
        $currentCategory = Category::select('name')->where('user_id', $user->id)->where('key_id', $key->id)->first();
        $keyOwner = User::where('id', $key->owner_id)->first();
        $hasAdminAccess = KeyAccessRequest::where('admin_id', $user->id)->where('user_email', $keyOwner->email)->exists();
        $isSharedKey = SharedKey::where('key_id', $key->id)->where('shared_email', $user->email)->exists();
        
        try {
            $key->value = Crypt::decryptString($key->value);
        } catch (DecryptException $e) {
            //
        }

        return Inertia::render('Keys/Show', [
            'skey' => $key->load('user'),
            'shared_users' => $shared_users,
            'categories' => $this->categories()->map(function ($cat) {
                return [
                    'name' => $cat->name
                ];
            }),
            'currentCategory' => $currentCategory,
            'hasAdminAccess' => $hasAdminAccess,
            'isSharedKey' => $isSharedKey
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
            SharedKey::where('key_id', $key->id)->delete();
        }
        else
        {
            Category::where('key_id', $key->id)->where('user_id', '!=', $key->owner_id)->delete();
        }

        return back(303);
    }

    /**
     * Delete a key and its connected shared keys and categories.
     *
     * @param \App\Models\Key $key
     * @return \Illuminate\Http\Response
     */
    public function delete(Key $key)
    {   
        Key::where('id', $key->id)->firstorfail()->delete();
        SharedKey::where('key_id', $key->id)->delete();
        Category::where('key_id', $key->id)->delete();

        return redirect()->route('key.index');
    }

    /**
     * Get the categories for a user.
     *
     * @return array
     */
    private function categories()
    {
        $user = auth()->user();

        return Category::select('name')->where('user_id', $user->id)->groupby('name')->get();
    }

    /**
     * Get the keys for a category.
     *
     * @param String $cat
     * @return array
     */
    private function categoryKeys(String $cat)
    {
        $user = auth()->user();
        $categoryKeys = Category::where('user_id', $user->id)->where('name', $cat)->get();

        return Key::whereIn('id', $categoryKeys->map(function ($key) { return ['id' => $key->key_id]; })->pluck('id'))->get();
    }

    /**
     * Get the keys for all categories.
     *
     * @return array
     */
    private function allCategoryKeys()
    {
        $user = auth()->user();
        $categoryKeys = Category::where('user_id', $user->id)->get();

        return Key::whereIn('id', $categoryKeys->map(function ($key) { return ['id' => $key->key_id]; })->pluck('id'))->get();
    }

    /**
     * Get the allowed personal, public, and shared keys for the user that are not in a category.
     *
     * @return array
     */
    private function allowedKeys()
    {
        $user = auth()->user();
        $sharedKeys = SharedKey::select('*')->where('shared_email', '=', $user->email)->get();
        $categoryKeys = Category::where('user_id', $user->id)->get();
        
        return Key::where('user_id', $user->id)
            ->whereNotIn('id', $categoryKeys->map(function ($key) { return ['id' => $key->key_id]; })->pluck('id'))
            ->orWhere('public', true)
            ->whereNotIn('id', $categoryKeys->map(function ($key) { return ['id' => $key->key_id]; })->pluck('id'))
            ->orWhereIn('id', $sharedKeys->map(function ($key) { return ['id' => $key->key_id]; })->pluck('id'))
            ->whereNotIn('id', $categoryKeys->map(function ($key) { return ['id' => $key->key_id]; })->pluck('id'))
            ->get();
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
}