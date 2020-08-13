<?php

namespace Tests\Unit\Rules;

use Illuminate\Http\Testing\FileFactory;
use Orchestra\Testbench\TestCase;
use ProtoneMedia\LaravelMixins\Rules\RelaxedDimensions;

class RelaxedDimensionsTest extends TestCase
{
    /** @test */
    public function it_works_with_repeting_decimals()
    {
        $rule = RelaxedDimensions::make()->ratio(20 / 9)->factor(5);

        $factory = new FileFactory;

        $this->assertTrue($rule->passes('attribute', $factory->image('attribute', 2000, 900)));

        $this->assertTrue($rule->passes('attribute', $factory->image('attribute', 2000, 901)));
        $this->assertTrue($rule->passes('attribute', $factory->image('attribute', 2000, 899)));
        $this->assertFalse($rule->passes('attribute', $factory->image('attribute', 2000, 902)));
        $this->assertFalse($rule->passes('attribute', $factory->image('attribute', 2000, 898)));

        $this->assertTrue($rule->passes('attribute', $factory->image('attribute', 4000, 1801)));
        $this->assertTrue($rule->passes('attribute', $factory->image('attribute', 4000, 1799)));
        $this->assertFalse($rule->passes('attribute', $factory->image('attribute', 4000, 1802)));
        $this->assertFalse($rule->passes('attribute', $factory->image('attribute', 4000, 1798)));

        $this->assertTrue($rule->passes('attribute', $factory->image('attribute', 8000, 3599)));
        $this->assertTrue($rule->passes('attribute', $factory->image('attribute', 8000, 3601)));
        $this->assertFalse($rule->passes('attribute', $factory->image('attribute', 8000, 3602)));
        $this->assertFalse($rule->passes('attribute', $factory->image('attribute', 8000, 3598)));
    }
}
