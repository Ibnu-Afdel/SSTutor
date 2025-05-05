<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;


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
        // $socialUser = Socialite::driver($provider)->user();
        $socialUser = Socialite::driver($provider)->stateless()->user();


        $user = User::firstOrCreate(
            [
                'email' => $socialUser->getEmail()
            ],
            [
                'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                'username' => $this->generateUsername($socialUser),
                'password' => bcrypt(uniqid()),
                // 'provider' => $provider,
                // 'provider_id' => $socialUser->getId(),
                'role' => 'student',
                'status' => 'approved',
                'is_pro' => false,
            ]
        );
        Auth::login($user);
        return redirect()->intended(route('user.profile', ['username' => auth()->user()->username]));
    }

    protected function generateUsername($socialUser)
    {
        $base = Str::slug($socialUser->getName());
        $random = Str::random(4);

        $username = $base . $random;
        while (User::where('username', $username)->exists()) {
            $random = Str::random(4);
            $username = $base . $random;
        }
        return $username;
    }
}
