<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\UserSeeder;

class LoginTest extends DuskTestCase
{
    // use RefreshDatabase;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {

        $this->seed(UserSeeder::class);

        $user = User::factory()->create([
            'email' => 'kkadievstojan@gmail.com',
            'roles_id' => 1
        ]);

        $this->browse(function ($browser) use ($user) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'gazdapari10!')
                    ->press('Login')
                    ->assertPathIs('/home');
        });
    }
}
