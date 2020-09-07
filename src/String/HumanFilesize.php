<?php

namespace ProtoneMedia\LaravelMixins\String;

class HumanFilesize
{
    public function humanFilesize()
    {
        return function ($value, $precision = 1): string {
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

            return number_format($value, $precision) . ' ' . $unit;
        };
    }
}
