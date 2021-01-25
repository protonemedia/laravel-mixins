<?php

namespace ProtoneMedia\LaravelMixins\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Pdp\Rules;

class HostOrSubdomain implements Rule
{
    private array $hosts;

    private $publicSuffixList;

    /**
     * Create a new rule instance.
     *
     * @param array|string $host
     */
    public function __construct($host, Rules $publicSuffixList = null)
    {
        $this->hosts = Arr::wrap($host);

        $this->publicSuffixList = $publicSuffixList ?: Rules::fromString(
            Http::get('https://publicsuffix.org/list/public_suffix_list.dat')
        );
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

        $resolvedDomain = $this->publicSuffixList->resolve($host);

        if ($host !== $resolvedDomain->domain()->toString()) {
            return false;
        }

        return in_array($resolvedDomain->registrableDomain()->toString(), $this->hosts);
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
