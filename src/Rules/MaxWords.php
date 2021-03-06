<?php

namespace ProtoneMedia\LaravelMixins\Rules;

use Illuminate\Contracts\Validation\Rule;

class MaxWords implements Rule
{
    private int $max;

    /**
     * Create a new rule instance.
     *
     * @param integer $max
     */
    public function __construct(int $max)
    {
        $this->max = $max;
    }

    /**
     * Helper method to initialize the rule.
     *
     * @param integer $max
     * @return self
     */
    public static function make(int $max): self
    {
        return new static($max);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return count(array_filter(explode(' ', $value))) <= $this->max;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.max_words');
    }
}
