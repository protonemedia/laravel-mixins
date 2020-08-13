<?php

namespace ProtoneMedia\LaravelMixins\Rules;

use Illuminate\Contracts\Validation\Factory;
use Illuminate\Contracts\Validation\Rule;

class UrlWithoutScheme implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $scheme = @parse_url($value, PHP_URL_SCHEME);

        if (!$scheme) {
            $value = 'https://' . $value;
        }

        return app(Factory::class)->make([], [])->validateUrl($attribute, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.url');
    }
}
