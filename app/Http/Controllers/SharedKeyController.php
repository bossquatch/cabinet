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

class SharedKeyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Share a key with another user.
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
            $this->ensureEmailNotOwner($input)
        )->after(
            $this->ensureKeyNotAlreadyShared($input)
        )->validateWithBag('shareUserKey');

        $sharedKey = SharedKey::create($input);

        return redirect()->route('key.show', ['key' => $sharedKey->key_id]);
    }

    /**
     * Share a key with the current team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \App\Models\Team $team
     * @return \Illuminate\Http\Response
     */
    public function teamShare(Request $request, Team $team)
    {
        $input = $request->all();
        $users = $team->allUsers();
        $currentUser = auth()->user();

        foreach ($users as $user)
        {
            if ($user->id != $currentUser->id)
            {
                $userInfo = User::where('id', $user->id)->firstorfail();
                $input['shared_email'] = $userInfo->email;
                
                $alreadyShared = SharedKey::where('key_id', $input['key_id'])
                    ->where('shared_email', $input['shared_email'])
                    ->exists();

                if (!$alreadyShared)
                {
                    SharedKey::create($input);
                }
            }
        }

        return redirect()->route('key.show', ['key' => $input['key_id']]);
    }

    /**
     * Revoke a user's access to a key.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \App\Models\Key $key
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function revoke(Request $request, Key $key, User $user)
    {   
        SharedKey::where('key_id', $key->id)->where('shared_email', $user->email)->firstorfail()->delete();

        Category::where('user_id', $user->id)->where('key_id', $key->id)->delete();

        return redirect()->route('key.show', ['key' => $key->id]);
    }

    /**
     * Ensure that the email exists.
     *
     * @param  mixed  $input
     * @return \Closure
     */
    protected function ensureEmailExists(mixed $input)
    {
        $email = User::where('email', $input['shared_email'])->exists();

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
    protected function ensureEmailNotOwner(mixed $input)
    {
        $ownerEmail = false;
        $key = Key::where('id', $input['key_id'])->first();
        $owner = User::where('id', $key->owner_id)->first();

        if ($input['shared_email'] == $owner->email)
        {
            $ownerEmail = true;
        }

        return function ($validator) use ($ownerEmail) {
            $validator->errors()->addIf(
                $ownerEmail,
                'shared_email',
                __('Cannot share key with the owner.')
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
        $shared_key = SharedKey::where('key_id', $input['key_id'])
            ->where('shared_email', $input['shared_email'])
            ->exists();

        return function ($validator) use ($shared_key) {
            $validator->errors()->addIf(
                $shared_key,
                'shared_email',
                __('This key is already being shared with this user.')
            );
        };
    }
}