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

    /**
     * Helper method to initialize the rule.
     *
     * @param array $constraints
     * @return self
     */
    public static function make(array $constraints = []): self
    {
        return new static($constraints);
    }

    /**
     * Set the "margin" constraint.
     *
     * @param integer $margin
     * @return void
     */
    public function margin(int $margin)
    {
        if ($margin > 0) {
            $this->constraints['margin'] = $margin;
        }

        return $this;
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
        $parameters = Collection::make($this->constraints)->map(function ($value, $key) {
            return "{$key}={$value}";
        })->all();

        return $this->validateDimensions($attribute, $value, $parameters);
    }

    /**
     * It checks for every margin if the underlying rule doesn't fails.
     *
     * @param  array  $parameters
     * @param  int  $width
     * @param  int  $height
     * @return bool
     */
    protected function failsRatioCheck($parameters, $width, $height)
    {
        if (!isset($parameters['ratio']) || !isset($parameters['margin'])) {
            return $this->failsRatioCheckTrait($parameters, $width, $height);
        }

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
