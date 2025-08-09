<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;


class OAuthController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        if (request()->has('error') || !request()->has('code')) {
            return redirect()->route('login');
        }
        $socialUser = Socialite::driver($provider)->stateless()->user();


        $user = User::firstOrCreate(
            [
                'email' => $socialUser->getEmail()
            ],
            [
                'name' => $socialUser->getName(),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'role' => 'student',
                'status' => 'approved',
                'is_pro' => false,
            ]
        );
        Auth::login($user);
        return redirect()->intended(route('user.profile', ['username' => auth()->user()->username]));
    }

}
