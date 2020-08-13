<?php

namespace ProtoneMedia\LaravelMixins\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Validation\Concerns\ValidatesAttributes;
use Illuminate\Validation\Rules\Dimensions as RulesDimensions;

class RelaxedDimensions extends RulesDimensions implements Rule
{
    use ValidatesAttributes;

    public static function make(array $constraints = []): self
    {
        return new static($constraints);
    }

    public function factor($factor)
    {
        $this->constraints['factor'] = $factor;

        return $this;
    }

    public function passes($attribute, $value)
    {
        $parameters = Collection::make($this->constraints)->map(function ($value, $key) {
            return "{$key}={$value}";
        })->all();

        return $this->validateDimensions($attribute, $value, $parameters);
    }

    protected function failsRatioCheck($parameters, $width, $height)
    {
        if (! isset($parameters['ratio'])) {
            return false;
        }

        $parameters['factor'] = $parameters['factor'] ?? 1;

        [$numerator, $denominator] = array_replace(
            [1, 1],
            array_filter(sscanf($parameters['ratio'], '%f/%d'))
        );

        $precision = (1 / max($width, $height)) * $parameters['factor'];

        return abs($numerator / $denominator - $width / $height) > $precision;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.dimensions');
    }
}
