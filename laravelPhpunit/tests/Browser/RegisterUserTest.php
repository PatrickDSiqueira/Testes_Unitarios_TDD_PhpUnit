<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;

class RegisterUserTest extends DuskTestCase
{
    /**
     *
     * @teste
     */
    public function check_if_root_site_is_correct()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Laravel');
        });
    }

    /**
     * @test
     */

    public function check_if_login_function_is_working(){

        $this->browser(function (Browser $browser){
            $browser->visit('/login')
                ->type('email','teste@tes')
                ->type('password','teste@tes')
                ->press('Login')
                ->assertPathIs('/home');
        });
    }
}
