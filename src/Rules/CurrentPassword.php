<?php

namespace ProtoneMedia\LaravelMixins\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CurrentPassword implements Rule
{
    use ThrottlesLogins;

    private bool $tooManyAttempts = false;

    /**
     * Get the throttle key for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function throttleKey(Request $request)
    {
        return Str::lower($request->user()->getAuthIdentifier() . '|' . $request->ip());
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
        $request = request();

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->tooManyAttempts = true;
            return false;
        }

        return tap(
            Hash::check($value, $request->user()->getAuthPassword()),
            fn ($verified) => $verified ? null : $this->incrementLoginAttempts($request)
        );
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if ($this->tooManyAttempts) {
            $seconds = $this->limiter()->availableIn(
                $this->throttleKey(request())
            );

            return __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]);
        }

        return __('validation.password');
    }
}
