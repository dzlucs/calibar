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

    public function update_drink_unsuccesfully(AcceptanceTester $page): void
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
        $page->makeScreenshot('before_icon_click2');

        $page->fillField('#drink_name', '');
        $page->fillField('#drink_price', '29.90');
        $page->click('Salvar');

        $page->makeScreenshot('after_icon_click2');
        $page->see('O campo nome não pode estar vazio!');

        $page->fillField('#drink_name', 'Novo drink de teste');
        $page->fillField('#drink_price', '');
        $page->click('Salvar');

        $page->makeScreenshot('after_icon_click3');

        $page->see('O campo preço não pode estar vazio!');
    }

    public function list_all_drinks(AcceptanceTester $page): void
    {
        $user = new User([
            'name' => 'Admin test',
            'email' => 'admin@test.com',
            'password' => '123',
            'password_confirmation' => '123'
        ]);
        $user->save();

        $admin = new Admin(['user_id' => $user->id]);
        $admin->save();

        $page->login($user->email, $user->password);

        $page->amOnPage('/admin/drinks');

        $page->amOnPage('/admin/drinks');

        $page->click('Adicionar drink');
        $page->fillField('#drink_name', 'Drink 1');
        $page->fillField('#drink_price', '29.90');
        $page->click('Adicionar');
        $page->waitForText('Drink registrado com sucesso!');

        $page->click('Adicionar drink');
        $page->fillField('#drink_name', 'Drink 2');
        $page->fillField('#drink_price', '39.90');
        $page->click('Adicionar');
        $page->waitForText('Drink registrado com sucesso!');

        $page->amOnPage('/admin/drinks');

        $page->see('Drink 1');
        $page->see('Drink 2');

        $page->makeScreenshot('list_all_drinks');
    }

    public function test_drinks_pagination(AcceptanceTester $page): void
    {
        $user = new User([
            'name' => 'Admin 1',
            'email' => 'admin@one.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $user->save();

        $admin = new Admin(['user_id' => $user->id]);
        $admin->save();

        $page->login($user->email, $user->password);

        $page->amOnPage('/admin/drinks');

        $page->makeScreenshot('page_before');

        for ($i = 1; $i <= 10; $i++) {
            $page->click('Adicionar drink');

            $page->fillField('#drink_name', 'Drink de teste' . $i);
            $page->fillField('#drink_price', '49.90');

            $page->click('Adicionar');
            $page->waitForText('Drink registrado com sucesso!');
        }

        $page->makeScreenshot('page_after');

        //problemas com overlay das divs e paginator
        $page->executeJS('
            const el = document.querySelector("a.page-link[href=\'/admin/drinks/page/2\']");
            if(el){
                el.scrollIntoView();
                el.click();
            }
        ');

        $page->seeInCurrentUrl('/admin/drinks/page/2');

        $page->makeScreenshot('page_2');
    }
}