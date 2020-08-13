<?php

namespace ProtoneMedia\LaravelMixins\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Validation\Concerns\ValidatesAttributes;
use Illuminate\Validation\Rules\Dimensions as RulesDimensions;

class DimensionsWithMargin extends RulesDimensions implements Rule
{
    use ValidatesAttributes {
        failsRatioCheck as failsRatioCheckTrait;
    }

    public static function make(array $constraints = []): self
    {
        return new static($constraints);
    }

    public function margin($margin)
    {
        if ($margin > 0) {
            $this->constraints['margin'] = $margin;
        }

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
        if (!isset($parameters['ratio'])) {
            return false;
        }

        if (isset($parameters['margin'])) {
            $range = range($parameters['margin'] * -1, $parameters['margin']);

            foreach ($range as $margin) {
                if (!$this->failsRatioCheckTrait($parameters, $width + $margin, $height)) {
                    return false;
                }

                if (!$this->failsRatioCheckTrait($parameters, $width, $height + $margin)) {
                    return false;
                }
            }

            return true;
        }

        return $this->failsRatioCheckTrait($parameters, $width, $height);
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
