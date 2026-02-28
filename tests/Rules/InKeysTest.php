<?php

namespace ProtoneMedia\Mixins\Tests\Rules;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Facades\Validator;
use Orchestra\Testbench\TestCase;
use ProtoneMedia\LaravelMixins\Rules\InKeys;

class InKeysTest extends TestCase
{
    #[Test]
    /** @test */
    public function it_validates_the_keys()
    {
        $rule = InKeys::make([
            'a' => 'foo',
            'b' => 'bar',
            'c' => 'baz',
        ]);

        $validator = Validator::make([], ['attribute' => $rule]);

        $this->assertTrue($validator->setData(['attribute' => 'a'])->passes());
        $this->assertFalse($validator->setData(['attribute' => 'd'])->passes());
        $this->assertFalse($validator->setData(['attribute' => 'foo'])->passes());
    }
}
