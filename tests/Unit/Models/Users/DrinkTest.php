<?php

namespace Tests\Unit\Models\Drinks;

use App\Models\Drink;
use App\Models\Admin;
use App\Models\User;
use Tests\TestCase;

class DrinkTest extends TestCase
{
    private Drink $drink;
    private User $user;
    private Admin $admin;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = new User([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);
        $this->user->save();

        $this->admin = new Admin([
            'user_id' => $this->user->id
        ]);
        $this->admin->save();

        $this->drink = new Drink([
            'name' => 'drink test 1',
            'price' => '49.90',  
            'admin_id' => $this->admin->id
        ]);
        $this->drink->save();
    }

    public function test_should_create_new_drink(): void
    {
        $drink2 = $this->admin->drinks()->new([
            'name' => 'drink test 2',
            'price' => '39.90',  
        ]);
        $this->assertTrue($drink2->save());
        $this->assertCount(2, Drink::all());
    }

        public function test_destroy_should_delete_a_drink(): void
    {
        $drink2 = $this->admin->drinks()->new([
            'name' => 'drink test 2',
            'price' => '39.90',  
        ]);
        $drink2->save();
        $this->assertCount(2, Drink::all());

        $drink2->destroy();
        $this->assertCount(1, Drink::all());
    }

    public function test_should_return_all_drinks(): void
    {
        $drinks[] = $this->drink;
        $drinks[] = $this->admin->drinks()->new([
            'name' => 'drink test 2',
            'price' => '39.90',  
        ]);
        $drinks[1]->save();

        $all = Drink::all();
        $this->assertCount(2, $all);
        $this->assertEquals($drinks, $all);
    }
}
