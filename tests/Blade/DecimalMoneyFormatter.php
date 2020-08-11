<?php

namespace Protonemedia\Mixins\Tests\Blade;

use Orchestra\Testbench\TestCase;
use ProtoneMedia\LaravelMixins\Blade\DecimalMoneyFormatter;
use ProtoneMedia\LaravelMixins\Blade\TestsBladeComponents;

class DecimalMoneyFormatterTest extends TestCase
{
    use TestsBladeComponents;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setViewPath(__DIR__ . '/money');

        DecimalMoneyFormatter::directive();
    }

    /** @test */
    public function it_can_format_cents_in_decimals()
    {
        $this->assertEquals('0.99', $this->renderView('decimals', ['cents' => 99]));
        $this->assertEquals('1.00', $this->renderView('decimals', ['cents' => 100]));
        $this->assertEquals('100', $this->renderView('decimals', ['cents' => 100, 'code' => 'XTS']));

        // or set a default
        DecimalMoneyFormatter::directive('decimals', 'XTS');
        $this->assertEquals('100', $this->renderView('decimals', ['cents' => 100]));
    }
}
