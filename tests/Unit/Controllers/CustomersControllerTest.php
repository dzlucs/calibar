<?php

namespace Tests\Unit\Controllers;

use App\Models\Customer;
use App\Models\User;

class CustomersControllerTest extends ControllerTestCase
{
    private User $user1;
    private Customer $customer;

    public function setUp(): void
    {
        parent::setUp();

        $this->user1 = new User([
            'name' => 'User 1',
            'email' => 'user1@example.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $this->user1->save();

        $this->customer = new Customer([
            'user_id' => $this->user1->id,
        ]);
        $this->customer->save();

        $_SESSION['user']['id'] = $this->user1->id;
    }

    public function test_index(): void
    {
        $response = $this->get(action: 'index', controllerName: 'App\Controllers\CustomerController');

        $this->assertStringContainsString('Olá de novo', $response);
    }
}
