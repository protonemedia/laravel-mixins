<?php

namespace Protonemedia\Mixins\Tests;

use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase;
use ProtoneMedia\LaravelMixins\String\Url;

class UrlTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Str::mixin(new Url);
    }

    /** @test */
    public function it_prepends_to_protocol()
    {
        $this->assertEquals(null, Str::url(null));
        $this->assertEquals('', Str::url(''));

        $this->assertEquals('https://google.nl', Str::url('https://google.nl'));
        $this->assertEquals('http://google.nl', Str::url('http://google.nl'));
        $this->assertEquals('https://google.nl', Str::url('google.nl'));
    }
}
