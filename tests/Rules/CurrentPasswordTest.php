<?php

namespace ProtoneMedia\Mixins\Tests\Rules;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Orchestra\Testbench\TestCase;
use ProtoneMedia\LaravelMixins\Rules\CurrentPassword;

class CurrentPasswordTest extends TestCase
{
    /** @test */
    public function it_verifies_the_current_password_the_authenticated_user()
    {
        if (version_compare(app()->version(), 9, '>=')) {
            return $this->markTestSkipped("Built-in Laravel 9");
        }

        Artisan::call('cache:clear');
        User::unguard();

        $user = new User([
            'password' => bcrypt('secret'),
        ]);

        $request = Request::createFromGlobals()->setUserResolver(fn () => $user);
        app()->bind('request', fn () => $request);

        $rule = new CurrentPassword;

        $this->assertFalse($rule->passes('current_password', 'wrong'));
        $this->assertTrue($rule->passes('current_password', 'secret'));
    }

    /** @test */
    public function it_throttles_the_attempts()
    {
        if (version_compare(app()->version(), 9, '>=')) {
            return $this->markTestSkipped("Built-in Laravel 9");
        }

        Artisan::call('cache:clear');
        User::unguard();

        $user = new User([
            'password' => bcrypt('secret'),
        ]);

        $request = Request::createFromGlobals()->setUserResolver(fn () => $user);
        app()->bind('request', fn () => $request);

        $rule = new CurrentPassword;

        foreach (range(1, 5) as $i) {
            $this->assertFalse($rule->passes('current_password', 'wrong'));
            $this->assertEquals('The password is incorrect.', $rule->message());
        }

        // trigger
        $this->assertFalse($rule->passes('current_password', 'wrong'));
        $this->assertStringContainsString('Too many login attempts.', $rule->message());
    }
}
