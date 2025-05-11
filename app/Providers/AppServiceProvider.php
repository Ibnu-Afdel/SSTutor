<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use Cloudinary\Configuration\Configuration;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Using CLOUDINARY_URL
        // if (config('cloudinary.cloud_url')) {
        //     Configuration::instance(config('cloudinary.cloud_url'));
        // }

        // configuring manually:
        Configuration::instance([
            'cloud' => [
                'cloud_name' => config('cloudinary.cloud_name'),
                'api_key'    => config('cloudinary.api_key'),
                'api_secret' => config('cloudinary.api_secret'),
            ],
            'url' => [
                'secure' => true,
            ],
        ]);
        // User::observe(UserObserver::class);
        Gate::define('create', function ($user) {
            return  $user->role === 'instructor';
        });

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
