<?php

namespace ProtoneMedia\LaravelMixins\Blade;

use Illuminate\Support\Facades\Blade;

trait BladeDirectiveHelpers
{
    public static function parseExpression(string $expression, array $defaults = []): array
    {
        $parts = explode(',', Blade::stripParentheses($expression));

        foreach ($defaults as $key => $default) {
            $parts[$key] = isset($parts[$key]) ? trim($parts[$key]) : $default;
        }

        return $parts;
    }
}
