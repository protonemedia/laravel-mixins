<?php

namespace Protonemedia\Mixins\Tests\Blade;

use Orchestra\Testbench\TestCase;
use ProtoneMedia\LaravelMixins\Blade\IntlMoneyFormatter;
use ProtoneMedia\LaravelMixins\Blade\TestsBladeComponents;

class IntlFormatterTest extends TestCase
{
    use TestsBladeComponents;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setViewPath(__DIR__ . '/templates');

        IntlMoneyFormatter::directive();
    }

    private static function replaceNonBreakingSpace(string $value): string
    {
        return str_replace("\u{00A0}", " ", $value);
    }

    /** @test */
    public function it_has_a_blade_directive_to_format_money()
    {
        // @money(99)
        $this->assertEquals('€ 0,99', static::replaceNonBreakingSpace($this->renderView('intl', ['cents' => 99])));
        $this->assertEquals('€ 1,00', static::replaceNonBreakingSpace($this->renderView('intl', ['cents' => 100])));
        $this->assertEquals('€ 1.000,00', static::replaceNonBreakingSpace($this->renderView('intl', ['cents' => 100 * 1000])));

        // @money(99, 'USD')
        $this->assertEquals('US$ 0,99', static::replaceNonBreakingSpace($this->renderView('intl', ['cents' => 99, 'code' => 'USD'])));
        $this->assertEquals('US$ 1,00', static::replaceNonBreakingSpace($this->renderView('intl', ['cents' => 100, 'code' => 'USD'])));
        $this->assertEquals('US$ 1.000,00', static::replaceNonBreakingSpace($this->renderView('intl', ['cents' => 100 * 1000, 'code' => 'USD'])));

        // or set a default
        IntlMoneyFormatter::directive('money', 'USD');
        $this->assertEquals('US$ 0,99', static::replaceNonBreakingSpace($this->renderView('intl', ['cents' => 99])));

        // @money(99, 'USD', 'en-US')
        $this->assertEquals('$0.99', static::replaceNonBreakingSpace($this->renderView('intl', ['cents' => 99, 'code' => 'USD', 'locale' => 'en-US'])));
        $this->assertEquals('1,00 $', static::replaceNonBreakingSpace($this->renderView('intl', ['cents' => 100, 'code' => 'USD', 'locale' => 'de-DE'])));
        $this->assertEquals('US$ 1.000,00', static::replaceNonBreakingSpace($this->renderView('intl', ['cents' => 100 * 1000, 'code' => 'USD'])));
        $this->assertEquals('1 000,00 $US', static::replaceNonBreakingSpace($this->renderView('intl', ['cents' => 100 * 1000, 'code' => 'USD', 'locale' => 'fr-FR'])));

        // or set a default
        IntlMoneyFormatter::directive('money', 'USD', 'fr-FR');
        $this->assertEquals('1 000,00 $US', static::replaceNonBreakingSpace($this->renderView('intl', ['cents' => 100 * 1000])));
    }
}
