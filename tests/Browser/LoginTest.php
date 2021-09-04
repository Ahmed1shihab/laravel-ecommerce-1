<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    // use DatabaseMigrations;
    
    // TODO: add @test commet to this method
    public function a_user_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $this->browse(function (Browser $browser) use($user) {
            $browser->visit('/')
                    ->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'wrong-password')
                    ->press('Login')
                    ->assertPathIs('/login')
                    ->assertSee('credentials do not match');
        });
    }
    
    // TODO: add @test commet to this method
    public function a_user_cannot_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $this->browse(function (Browser $browser) use($user) {
            $browser->visit('/')
                    ->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'password')
                    ->press('Login')
                    ->assertPathIs('/');
        });
    }
}
