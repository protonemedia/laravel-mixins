<?php

namespace ProtoneMedia\LaravelMixins\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Host implements Rule
{
    private array $hosts;

    /**
     * Create a new rule instance.
     *
     * @param array|string $host
     */
    public function __construct($host)
    {
        $this->hosts = Arr::wrap($host);
    }

    public static function make($host): self
    {
        return new static($host);
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
        if (!parse_url($value, PHP_URL_SCHEME)) {
            $value = "https://{$value}";
        }

        $host = parse_url($value, PHP_URL_HOST);

        if (!$host) {
            return false;
        }

        if (Str::startsWith($host, 'www.')) {
            $host = Str::after($host, 'www.');
        }

        return in_array($host, $this->hosts);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.host');
    }
}
