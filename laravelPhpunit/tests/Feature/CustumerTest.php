<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustumerTest extends TestCase
{
    /**
     *
     * @test
     *
     */
    public function only_logged_in_users_can_see_customers_list()
    {
        $response = $this->get('/customers')
            ->assertRedirect('/login');
    }
}
