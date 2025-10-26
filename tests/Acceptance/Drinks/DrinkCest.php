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

/*         $this->drink = new Drink([
            'name' => 'drink teste',
            'price' => '29,90'
        ]);
        $this->drink->save(); */
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

    public function create_drink_unsuccessfully(AcceptanceTester $page): void
    {
        $this->setUp();

        $page->login($this->user->email, $this->user->password);

        $page->amOnPage('/admin/drinks');
        
        $page->click('Adicionar drink');

        $page->amOnPage('/admin/drinks/new');

        $page->fillField('#drink_name', '');
        $page->fillField('#drink_price', '49.90');
        $page->click('Adicionar');
        $page->see('O campo nome não pode estar vazio!');

        $page->fillField('#drink_name', 'Drink de teste');
        $page->fillField('#drink_price', '');
        $page->click('Adicionar');
        $page->see('O campo preço não pode estar vazio!');

        $page->fillField('#drink_name', '');
        $page->fillField('#drink_price', '');
        $page->click('Adicionar');
        $page->see('O campo preço não pode estar vazio!');
        $page->see('O campo nome não pode estar vazio!');
    }

    public function remove_drink(AcceptanceTester $page): void
    {
        $this->setUp();

        $page->login($this->user->email, $this->user->password);

        $page->amOnPage('/admin/drinks');
        
        $page->click('Adicionar drink');

        $page->fillField('#drink_name', 'Drink de teste');
        $page->fillField('#drink_price', '49.90');

        $page->click('Adicionar');
        $page->waitForText('Drink registrado com sucesso!');

        $page->see('Ver mais');
        $page->click('Ver mais');

        $page->waitForText('Drink de teste');

        $page->click("#trash-icon");

        $page->wait(2);
        $page->makeScreenshot('before_modal_click');
        $page->waitForElement('#deleteModal', 5);

        $page->click('#delete-btn-modal');
        $page->see('Drink removido com sucesso!');
    }

    public function update_drink_succesfully(AcceptanceTester $page): void
    {
        $this->setUp();

        $page->login($this->user->email, $this->user->password);

        $page->amOnPage('/admin/drinks');
        
        $page->click('Adicionar drink');

        $page->fillField('#drink_name', 'Drink de teste');
        $page->fillField('#drink_price', '49.90');

        $page->click('Adicionar');
        $page->waitForText('Drink registrado com sucesso!');

        $page->see('Ver mais');
        $page->click('Ver mais');

        $page->waitForText('Drink de teste');

        $page->click("#pencil-icon");

        $page->wait(2);
        $page->makeScreenshot('before_icon_click');

        $page->fillField('#drink_name', 'Novo drink de teste');
        $page->fillField('#drink_price', '29.90');
        $page->click('Salvar');

        $page->makeScreenshot('after_icon_click');
        $page->see('Drink editado com sucesso!');
    }
}