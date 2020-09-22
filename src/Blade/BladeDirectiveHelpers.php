<?php

namespace ProtoneMedia\LaravelMixins\Blade;

use Illuminate\Support\Facades\Blade;

trait BladeDirectiveHelpers
{
    /**
     * Strips the parentheses from the expression and splits the arguments into parts.
     * It returns an array containing the parts or the given defaults for each part.
     *
     * @param string $expression
     * @param array $defaults
     * @return array
     */
    public static function parseExpression(string $expression, array $defaults = []): array
    {
        $parts = explode(',', Blade::stripParentheses($expression));

        foreach ($defaults as $key => $default) {
            $parts[$key] = isset($parts[$key]) ? trim($parts[$key]) : $default;
        }

        return $parts;
    }
}
