<?php

namespace ProtoneMedia\Mixins\Tests\Database;
use PHPUnit\Framework\Attributes\Test;
use Orchestra\Testbench\TestCase;
use ProtoneMedia\LaravelMixins\Database\CountriesInDutch;

class CountriesInDutchTest extends TestCase
{
    #[Test]
    /** @test */
    public function it_returns_all_countries()
    {
        $this->assertNotEmpty((new CountriesInDutch)->all());
    }
}
