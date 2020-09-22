<?php

namespace ProtoneMedia\LaravelMixins\String;

use Illuminate\Support\Str;

class Url
{
    /**
     * Returns a function containing the macro.
     *
     * @return callable
     */
    public function url(): callable
    {
        /**
         * Prepends a default scheme to the url if it's missing.
         */
        return function ($value = null): ?string {
            if ($value && !Str::startsWith($value, ['http://', 'https://'])) {
                $value = 'https://' . $value;
            }

            return $value;
        };
    }
}
