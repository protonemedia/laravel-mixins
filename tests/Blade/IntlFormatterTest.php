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

        $this->setViewPath(__DIR__ . '/money');

        IntlMoneyFormatter::directive();
    }

    /** @test */
    public function it_has_a_blade_directive_to_format_money()
    {
        // @money(99)
        // $this->assertEquals('€ 0,99', $this->renderView('intl', ['cents' => 99]));
        // $this->assertEquals('€ 1,00', $this->renderView('intl', ['cents' => 100]));
        // $this->assertEquals('€ 1.000,00', $this->renderView('intl', ['cents' => 100 * 1000]));

        // @money(99, 'USD')
        $this->assertEquals('US$ 0,99', $this->renderView('intl', ['cents' => 99, 'code' => 'USD']));
        $this->assertEquals('US$ 1,00', $this->renderView('intl', ['cents' => 100, 'code' => 'USD']));
        $this->assertEquals('US$ 1.000,00', $this->renderView('intl', ['cents' => 100 * 1000, 'code' => 'USD']));

        // or set a default
        IntlMoneyFormatter::directive('money', 'USD');
        $this->assertEquals('US$ 0,99', $this->renderView('intl', ['cents' => 99]));

        // @money(99, 'USD', 'en')
        $this->assertEquals('$0.99', $this->renderView('intl', ['cents' => 99, 'code' => 'USD', 'locale' => 'en']));
        $this->assertEquals('1,00 $', $this->renderView('intl', ['cents' => 100, 'code' => 'USD', 'locale' => 'de']));
        $this->assertEquals('1 000,00 $US', $this->renderView('intl', ['cents' => 100 * 1000, 'code' => 'USD', 'locale' => 'fr']));

        // or set a default
        IntlMoneyFormatter::directive('money', 'USD', 'fr');
        $this->assertEquals('1 000,00 $US', $this->renderView('intl', ['cents' => 100 * 1000]));
    }
}
