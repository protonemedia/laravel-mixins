<?php

namespace ProtoneMedia\LaravelMixins\String;

class Compact
{
    public function compact()
    {
        return function ($value, int $eachSide = 32, string $seperator = ' ... '): string {
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
