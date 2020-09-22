<?php

namespace ProtoneMedia\Mixins\Tests\String;

use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase;
use ProtoneMedia\LaravelMixins\String\HumanFilesize;

class HumanFilesizeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Str::mixin(new HumanFilesize);
    }

    /** @test */
    public function it_can_format_file_size()
    {
        $this->assertEquals('456 Bytes', Str::humanFilesize(456));
        $this->assertEquals('999 Bytes', Str::humanFilesize(999));
        $this->assertEquals('1.0 KB', Str::humanFilesize(1000));
        $this->assertEquals('4.5 KB', Str::humanFilesize(4567));
        $this->assertEquals('44.6 KB', Str::humanFilesize(45678));
        $this->assertEquals('446.1 KB', Str::humanFilesize(456789));
        $this->assertEquals('3.3 MB', Str::humanFilesize(3456789));
        $this->assertEquals('1.8 GB', Str::humanFilesize(1932735283.2));
        $this->assertEquals('112,283.3 TB', Str::humanFilesize(123456789123456789));
    }
}
