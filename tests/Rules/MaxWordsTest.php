<?php

namespace Tests\Unit\Rules;

use Orchestra\Testbench\TestCase;
use ProtoneMedia\LaravelMixins\Rules\MaxWords;

class MaxWordsTest extends TestCase
{
    /** @test */
    public function it_counts_the_words()
    {
        $rule = MaxWords::make(250);

        $this->assertTrue($rule->passes('attribute', ''));
        $this->assertTrue($rule->passes('attribute', implode(' ', range(1, 250))));
        $this->assertFalse($rule->passes('attribute', implode(' ', range(1, 251))));
    }
}
