<?php

namespace Tests\Unit\Rules;

use Orchestra\Testbench\TestCase;
use ProtoneMedia\LaravelMixins\Rules\Host;

class HostTest extends TestCase
{
    /** @test */
    public function it_validates_the_host()
    {
        $rule = Host::make(['facebook.com', 'fb.me']);

        $this->assertTrue($rule->passes('attribute', 'facebook.com/123'));
        $this->assertTrue($rule->passes('attribute', 'http://facebook.com/123'));
        $this->assertTrue($rule->passes('attribute', 'http://www.facebook.com/123'));
        $this->assertTrue($rule->passes('attribute', 'https://facebook.com/123'));
        $this->assertTrue($rule->passes('attribute', 'https://facebook.com/123'));

        $this->assertTrue($rule->passes('attribute', 'fb.me/123'));
        $this->assertTrue($rule->passes('attribute', 'http://fb.me/123'));
        $this->assertTrue($rule->passes('attribute', 'https://fb.me/123'));
        $this->assertTrue($rule->passes('attribute', 'https://fb.me/123'));

        $this->assertFalse($rule->passes('attribute', 'twitter.com/123'));
        $this->assertFalse($rule->passes('attribute', 'http://twitter.com/123'));
        $this->assertFalse($rule->passes('attribute', 'https://twitter.com/123'));
        $this->assertFalse($rule->passes('attribute', 'https://twitter.com/123'));
    }
}
