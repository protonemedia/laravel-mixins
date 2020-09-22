<?php

namespace ProtoneMedia\LaravelMixins\String;

use Html2Text\Html2Text as BaseConverter;

class Html2Text extends BaseConverter
{
    protected function strtoupper($str)
    {
        return $str;
    }
}
