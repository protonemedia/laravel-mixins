<?php

namespace Tests\Unit\Rules;

use Illuminate\Http\Testing\FileFactory;
use Orchestra\Testbench\TestCase;
use ProtoneMedia\LaravelMixins\Rules\DimensionsWithMargin;

class DimensionsWithMarginTest extends TestCase
{
    /** @test */
    public function it_has_a_method_to_set_the_margin()
    {
        $rule = DimensionsWithMargin::make()->ratio(20 / 9)->margin(0);

        $factory = new FileFactory;

        $this->assertTrue($rule->passes('attribute', $factory->image('attribute', 2000, 900)));

        $this->assertFalse($rule->passes('attribute', $factory->image('attribute', 2001, 900)));
        $this->assertFalse($rule->passes('attribute', $factory->image('attribute', 1999, 900)));

        $this->assertFalse($rule->passes('attribute', $factory->image('attribute', 2000, 901)));
        $this->assertFalse($rule->passes('attribute', $factory->image('attribute', 2000, 899)));

        $rule->margin(2);

        $this->assertTrue($rule->passes('attribute', $factory->image('attribute', 2000, 900)));

        $this->assertTrue($rule->passes('attribute', $factory->image('attribute', 1998, 900)));
        $this->assertTrue($rule->passes('attribute', $factory->image('attribute', 1999, 900)));
        $this->assertTrue($rule->passes('attribute', $factory->image('attribute', 2000, 898)));
        $this->assertTrue($rule->passes('attribute', $factory->image('attribute', 2000, 899)));
        $this->assertTrue($rule->passes('attribute', $factory->image('attribute', 2000, 901)));
        $this->assertTrue($rule->passes('attribute', $factory->image('attribute', 2000, 902)));
        $this->assertTrue($rule->passes('attribute', $factory->image('attribute', 2001, 900)));
        $this->assertTrue($rule->passes('attribute', $factory->image('attribute', 2002, 900)));

        $this->assertFalse($rule->passes('attribute', $factory->image('attribute', 1997, 900)));
        $this->assertFalse($rule->passes('attribute', $factory->image('attribute', 2000, 903)));
    }
}
