<?php

/**
 * Auth controller  
 * @author Mustafa Omran <promustafaomran@hotmail.com >
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Socialite;
use App\User;

class AuthController extends Controller {

    /**
     * 
     * @param type $provider
     * @return type
     */
    public function redirectToProvider($provider) {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * 
     * @param string $provider
     * @return boolean
     */
    public function handleProviderCallback($provider) {
        $user = Socialite::driver($provider)->user();

        $authUser = $this->findOrCreateUser($user, $provider);
        Auth::login($authUser, true);
        return redirect($this->redirectTo);
    }

    
    /**
     * 
     * @param object $user
     * @param string $provider
     * @return object
     */
    function findOrCreateUser($user, $provider) {
        $authUser = User::where('provider_id', $user->id)->get();
        if ($authUser) {
            Auth::login($authUser != '');
            return redirect('home');
        }
        return User::create([
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'provider' => $provider,
                    'provider_id' => $user->id
        ]);
    }

}
