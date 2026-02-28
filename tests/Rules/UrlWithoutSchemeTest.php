<?php

namespace ProtoneMedia\Mixins\Tests\Rules;

use Orchestra\Testbench\TestCase;
use ProtoneMedia\LaravelMixins\Rules\UrlWithoutScheme;

class UrlWithoutSchemeTest extends TestCase
{
    /** @test */
    public function it_validates_the_url_without_the_procol()
    {
        $rule = new UrlWithoutScheme;

        $this->assertTrue($rule->passes('attribute', 'facebook.com'));
        $this->assertTrue($rule->passes('attribute', 'http://facebook.com'));
        $this->assertTrue($rule->passes('attribute', 'http://www.facebook.com'));
        $this->assertTrue($rule->passes('attribute', 'https://facebook.com'));
        $this->assertTrue($rule->passes('attribute', 'https://www.facebook.com'));
        $this->assertTrue($rule->passes('attribute', 'ftp://www.facebook.com'));

        $this->assertTrue($rule->passes('attribute', 'facebook.com/'));
        $this->assertTrue($rule->passes('attribute', 'http://facebook.com/'));
        $this->assertTrue($rule->passes('attribute', 'http://www.facebook.com/'));
        $this->assertTrue($rule->passes('attribute', 'https://facebook.com/'));
        $this->assertTrue($rule->passes('attribute', 'https://www.facebook.com/'));
        $this->assertTrue($rule->passes('attribute', 'ftp://www.facebook.com/'));

        $this->assertTrue($rule->passes('attribute', 'facebook.com/123'));
        $this->assertTrue($rule->passes('attribute', 'http://facebook.com/123'));
        $this->assertTrue($rule->passes('attribute', 'http://www.facebook.com/123'));
        $this->assertTrue($rule->passes('attribute', 'https://facebook.com/123'));
        $this->assertTrue($rule->passes('attribute', 'https://www.facebook.com/123'));
        $this->assertTrue($rule->passes('attribute', 'ftp://www.facebook.com/123'));

        $this->assertFalse($rule->passes('attribute', ''));
        $this->assertFalse($rule->passes('attribute', '1'));
        $this->assertFalse($rule->passes('attribute', 'http://1'));
        $this->assertFalse($rule->passes('attribute', 'https://1'));
    }
}
