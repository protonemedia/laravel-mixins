<?php

namespace ProtoneMedia\LaravelMixins\String;

class Text
{
    public function text()
    {
        return function ($value = null): string {
            $instance = new Html2Text($value ?: '', [
                'do_links' => 'none',
            ]);

            return trim($instance->getText());
        };
    }
}
