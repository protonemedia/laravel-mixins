<?php

namespace Protonemedia\Mixins\Tests\String;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase;
use ProtoneMedia\LaravelMixins\String\Text;

class TextTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Str::mixin(new Text);
    }

    #[Test]
    /** @test */
    public function it_converts_html_to_text()
    {
        $this->assertEquals('Protone Media', Str::text('<h1>Protone Media</h1>'));
    }
}
