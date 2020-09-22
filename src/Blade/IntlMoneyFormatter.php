<?php

namespace ProtoneMedia\LaravelMixins\Blade;

use Illuminate\Support\Facades\Blade;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter as BaseFormatter;
use Money\Money;
use NumberFormatter;

class IntlMoneyFormatter
{
    use BladeDirectiveHelpers;

    /**
     * Calls the format() method on the IntlMoneyFormatter with the given
     * amount of cents, currency code and locale.
     *
     * @param integer $cents
     * @param string $code
     * @param string $locale
     * @return string
     */
    public static function handler(int $cents, string $code, string $locale): string
    {
        $formatter = new BaseFormatter(
            new NumberFormatter($locale, NumberFormatter::CURRENCY),
            new ISOCurrencies
        );

        $money = Money::$code($cents);

        return $formatter->format($money);
    }

    /**
     * Registers a handler for the money formatter.
     *
     * @param string $name
     * @param string $code
     * @param string $locale
     * @return void
     */
    public static function directive(string $name = 'money', string $code = 'EUR', string $locale = 'nl_NL')
    {
        Blade::directive($name, function ($expression) use ($code, $locale) {
            $parts = static::parseExpression($expression, [
                1 => "'{$code}'",
                2 => "'{$locale}'",
            ]);

            return "<?php echo \ProtoneMedia\LaravelMixins\Blade\IntlMoneyFormatter::handler($parts[0], $parts[1], $parts[2]) ?>";
        });
    }
}
