<?php

namespace ProtoneMedia\LaravelMixins\Blade;

use Illuminate\Support\Facades\Blade;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter as BaseFormatter;
use Money\Money;

class DecimalMoneyFormatter
{
    use BladeDirectiveHelpers;

    /**
     * Calls the format() method on the DecimalMoneyFormatter with the given
     * amount of ccents and currency code.
     *
     * @param integer $cents
     * @param string $code
     * @return string
     */
    public static function handler(int $cents, string $code): string
    {
        $formatter = new BaseFormatter(new ISOCurrencies);

        $money = Money::$code($cents);

        return $formatter->format($money);
    }

    /**
     * Registers a handler for the money formatter.
     *
     * @param string $name
     * @param string $code
     * @return void
     */
    public static function directive(string $name = 'decimals', string $code = 'EUR')
    {
        Blade::directive($name, function ($expression) use ($code) {
            $parts = static::parseExpression($expression, [1 => "'{$code}'"]);

            return "<?php echo \ProtoneMedia\LaravelMixins\Blade\DecimalMoneyFormatter::handler($parts[0], $parts[1]) ?>";
        });
    }
}
