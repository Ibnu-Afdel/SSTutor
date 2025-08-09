<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;

class UsernameGenerator
{
    /**
     * Create a new class instance.
     */
    public static function generate($source)
    {
        $base = Str::slug(Str::before($source, '@'), '');

        if (empty($base)) {
            $base = 'user';
        }

        $username = $base;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = "$base$counter";
            $counter++;
        }

        return $username;
    }
}
