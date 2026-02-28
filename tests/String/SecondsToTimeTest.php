<?php

namespace ProtoneMedia\Mixins\Tests\String;

use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase;
use ProtoneMedia\LaravelMixins\String\SecondsToTime;

class SecondsToTimeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Str::mixin(new SecondsToTime);
    }

    /** @test */
    public function it_can_format_seconds_as_time()
    {
        $this->assertEquals('01:00:10', Str::secondsToTime(3610));
        $this->assertEquals('09:40', Str::secondsToTime(580));
        $this->assertEquals('00:01', Str::secondsToTime(1));
        $this->assertEquals('00:00', Str::secondsToTime(0));
        $this->assertEquals('00:09:40', Str::secondsToTime(580, false));
    }
}
