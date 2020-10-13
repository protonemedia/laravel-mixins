<?php

namespace ProtoneMedia\LaravelMixins\Rules;

use Illuminate\Validation\Rules\In;

class InKeys extends In
{
    /**
     * Create a new rule instance with the keys of the given values.
     *
     * @param  array  $values
     * @return void
     */
    public function __construct(array $values)
    {
        $this->values = array_keys($values);
    }

    public static function make(array $values): self
    {
        return new static($values);
    }
}
