<?php

namespace ProtoneMedia\LaravelMixins\String;

class Compact
{
    /**
     * Returns a function containing the macro.
     *
     * @return callable
     */
    public function compact(): callable
    {
        /**
         * Takes the first and last part of the string and glues them together with the seperator.
         */
        return function ($value, int $eachSide = 32, string $seperator = ' ... ') {
            if (strlen($value) <= $eachSide * 2) {
                return $value;
            }

            return implode($seperator, [
                substr($value, 0, $eachSide),
                substr($value, $eachSide * -1),
            ]);
        };
    }
}
