<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class OracleIDCSSocialiteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToOracleIDCS()
    {
        return Socialite::driver('oracle-idcs')->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function handleCallback()
    {
        try {

            $user = Socialite::driver('oracle-idcs')->verifiedUser();

            if($user === false) {
                return redirect()->route('login')->withErrors('User is not authorized to access this application.');
            }

            $finduser = User::where('email', $user['email'])->first();

            if($finduser){
                if (!$finduser->social_id) {
                    $finduser->update([
                        'social_id'=> $user['sub'],
                        'social_type'=> 'oracle-idcs',
                    ]);
                }

                Auth::login($finduser);

                return redirect()->route('dashboard');

            }else{
                $newUser = User::create([
                    'name' => ($user['given_name'] ?? '') . ' ' . ($user['family_name'] ?? ''),
                    'email' => $user['email'],
                    'social_id'=> $user['sub'] ?? null,
                    'social_type'=> 'oracle-idcs',
                    'password' => Hash::make(($user['family_name'] ?? '') . ($user['given_name'] ?? '') . ($user['employee_number'] ?? '')),
                ]);

                Auth::login($newUser);

                return redirect()->route('dashboard');
            }

        } catch (Exception $e) {
            abort(500, $e->getMessage());
        }
    }
}
