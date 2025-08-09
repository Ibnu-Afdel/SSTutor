<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    public function generateUniqueSlug($source, $slugColumn)
    {
        $slug = Str::slug($source);
        $originalSlug = $slug;
        $counter = 1;

        while(static::where($slugColumn, $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
