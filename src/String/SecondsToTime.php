<?php

namespace ProtoneMedia\LaravelMixins\String;

class SecondsToTime
{
    /**
     * Returns a function containing the macro.
     *
     * @return callable
     */
    public function secondsToTime(): callable
    {
        /**
         * Converts seconds to 'mm:ss' / 'hh:mm:ss' format.
         */
        return function (int $seconds, bool $omitHours = true): string {
            if (!$omitHours || $seconds >= 3600) {
                return sprintf('%02d:%02d:%02d', ($seconds / 3600), ($seconds / 60 % 60), $seconds % 60);
            }

            return sprintf('%02d:%02d', ($seconds / 60 % 60), $seconds % 60);
        };
    }
}
