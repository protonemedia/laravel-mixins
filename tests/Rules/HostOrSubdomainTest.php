<?php

namespace ProtoneMedia\Mixins\Tests\Rules;
use PHPUnit\Framework\Attributes\Test;
use Orchestra\Testbench\TestCase;
use ProtoneMedia\LaravelMixins\Rules\HostOrSubdomain;

class HostOrSubdomainTest extends TestCase
{
    #[Test]
    /** @test */
    public function it_validates_the_host()
    {
        $rule = HostOrSubdomain::make(['facebook.com', 'fb.me']);

        $this->assertTrue($rule->passes('attribute', 'facebook.com'));
        $this->assertTrue($rule->passes('attribute', 'http://facebook.com/123'));
        $this->assertTrue($rule->passes('attribute', 'http://www.facebook.com/123'));
        $this->assertTrue($rule->passes('attribute', 'https://facebook.com/123'));
        $this->assertTrue($rule->passes('attribute', 'https://facebook.com/123'));

        $this->assertTrue($rule->passes('attribute', 'sub.facebook.com/123'));
        $this->assertTrue($rule->passes('attribute', 'http://sub.facebook.com/123'));
        $this->assertTrue($rule->passes('attribute', 'http://www.sub.facebook.com/123'));
        $this->assertTrue($rule->passes('attribute', 'https://sub.facebook.com/123'));
        $this->assertTrue($rule->passes('attribute', 'https://sub.facebook.com/123'));

        $this->assertTrue($rule->passes('attribute', 'sub.sub.facebook.com/123'));
        $this->assertTrue($rule->passes('attribute', 'http://sub.sub.facebook.com/123'));
        $this->assertTrue($rule->passes('attribute', 'http://www.sub.sub.facebook.com/123'));
        $this->assertTrue($rule->passes('attribute', 'https://sub.sub.facebook.com/123'));
        $this->assertTrue($rule->passes('attribute', 'https://sub.sub.facebook.com/123'));

        $this->assertTrue($rule->passes('attribute', 'sub.fb.me/123'));
        $this->assertTrue($rule->passes('attribute', 'http://sub.fb.me/123'));
        $this->assertTrue($rule->passes('attribute', 'https://sub.fb.me/123'));
        $this->assertTrue($rule->passes('attribute', 'https://sub.fb.me/123'));

        $this->assertTrue($rule->passes('attribute', 'sub.sub.fb.me/123'));
        $this->assertTrue($rule->passes('attribute', 'http://sub.sub.fb.me/123'));
        $this->assertTrue($rule->passes('attribute', 'https://sub.sub.fb.me/123'));
        $this->assertTrue($rule->passes('attribute', 'https://sub.sub.fb.me/123'));

        $this->assertFalse($rule->passes('attribute', 'https://.fb.me/123'));
        $this->assertFalse($rule->passes('attribute', 'https://.sub.fb.me/123'));

        $this->assertFalse($rule->passes('attribute', 'twitter.com/123'));
        $this->assertFalse($rule->passes('attribute', 'http://twitter.com/123'));
        $this->assertFalse($rule->passes('attribute', 'https://twitter.com/123'));
        $this->assertFalse($rule->passes('attribute', 'https://twitter.com/123'));

        $this->assertFalse($rule->passes('attribute', 'sub.twitter.com/123'));
        $this->assertFalse($rule->passes('attribute', 'http://sub.twitter.com/123'));
        $this->assertFalse($rule->passes('attribute', 'https://sub.twitter.com/123'));
        $this->assertFalse($rule->passes('attribute', 'https://sub.twitter.com/123'));

        $this->assertFalse($rule->passes('attribute', 'sub.sub.twitter.com/123'));
        $this->assertFalse($rule->passes('attribute', 'http://sub.sub.twitter.com/123'));
        $this->assertFalse($rule->passes('attribute', 'https://sub.sub.twitter.com/123'));
        $this->assertFalse($rule->passes('attribute', 'https://sub.sub.twitter.com/123'));
    }
}
