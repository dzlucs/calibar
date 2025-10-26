<?php

namespace Tests\Acceptance\Drinks;

use App\Models\Admin;
use App\Models\Drink;
use App\Models\User;
use Tests\Acceptance\BaseAcceptanceCest;
use Tests\Support\AcceptanceTester;

class DrinkCest extends BaseAcceptanceCest
{
    private User $user;
    private Admin $admin;
    private Drink $drink;

    private function setUp(): void
    {
        $this->user = new User([
            'name' => 'Admin test',
            'email' => 'admin@test.com',
            'password' => '123',
            'password_confirmation' => '123'
        ]);
        
        $this->user->save();

        $this->admin = new Admin([
            'user_id' => $this->user->id
        ]);

        $this->admin->save();
    }

    public function create_drink_successfully(AcceptanceTester $page): void
    {
        $this->setUp();

        $page->login($this->user->email, $this->user->password);

        $page->amOnPage('/admin/drinks');
        
        $page->click('Adicionar drink');

        $page->fillField('#drink_name', 'Drink de teste');
        $page->fillField('#drink_price', '49.90');

        $page->click('Adicionar');
        $page->waitForText('Drink registrado com sucesso!');
    }
}