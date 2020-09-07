<?php

namespace ProtoneMedia\LaravelMixins\String;

use Illuminate\Support\Str;

class Url
{
    public function url()
    {
        return function ($value = null): ?string {
            if ($value && !Str::startsWith($value, ['http://', 'https://'])) {
                $value = 'https://' . $value;
            }

            return $value;
        };
    }
}
