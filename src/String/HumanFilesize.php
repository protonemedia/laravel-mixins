<?php

namespace ProtoneMedia\LaravelMixins\String;

class HumanFilesize
{
    /**
     * Returns a function containing the macro.
     *
     * @return callable
     */
    public function humanFilesize(): callable
    {
        /**
         * Formats the $value into a human readable filesize.
         */
        return function ($value, $precision = 1): string {
            $isNegative = $value < 0;

            $value = abs($value);

            if ($value >= 1000000000000) {
                $value = round($value / (1024 * 1024 * 1024 * 1024), $precision);
                $unit  = 'TB';
            } elseif ($value >= 1000000000) {
                $value = round($value / (1024 * 1024 * 1024), $precision);
                $unit  = 'GB';
            } elseif ($value >= 1000000) {
                $value = round($value / (1024 * 1024), $precision);
                $unit  = 'MB';
            } elseif ($value >= 1000) {
                $value = round($value / (1024), $precision);
                $unit  = 'KB';
            } else {
                $unit = 'Bytes';
                return number_format($value) . ' ' . $unit;
            }

            return ($isNegative ? '-' : '') . number_format($value, $precision) . ' ' . $unit;
        };
    }
}
