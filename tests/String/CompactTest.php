<?php

namespace ProtoneMedia\Mixins\Tests\String;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase;
use ProtoneMedia\LaravelMixins\String\Compact;

class CompactTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Str::mixin(new Compact);
    }

    #[Test]
    /** @test */
    public function it_can_compact_a_string()
    {
        $string = "Hoe simpeler hoe beter. Want hoe minder keuze je een speler laat, hoe groter de kans dat hij het juiste doet.";

        $this->assertEquals($string, Str::compact($string, 218));
        $this->assertEquals($string, Str::compact($string, 109));
        $this->assertEquals($string, Str::compact($string, 55));

        $this->assertEquals('Hoe simpeler hoe beter. Want hoe ... de kans dat hij het juiste doet.', Str::compact($string, 32));
        $this->assertEquals('Hoe simpeler hoe ... het juiste doet.', Str::compact($string, 16));
    }
}
