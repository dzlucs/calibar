<?php

namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;
use App\Models\User;
use App\Models\Admin;

class DrinkGalleryCest extends BaseAcceptanceCest
{
    public function testUploadValidImage(AcceptanceTester $I): void
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

        $I->login($user->email, '123');

        $I->amOnPage('/admin/drinks');
        $I->click('Adicionar drink');
        $I->fillField('#drink_name', 'Drink 1');
        $I->fillField('#drink_price', '29.90');
        $I->attachFile('#image_preview_input', 'avatar_test.jpeg');
        $I->click('Adicionar');
        $I->see('Imagem registrada com sucesso!');
        $I->seeElement('img[src*="avatar_test"]');
    }

    public function testRemoveImage(AcceptanceTester $I): void
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

        $I->login($user->email, '123');

        $I->amOnPage('/admin/drinks');
        $I->click('Adicionar drink');
        $I->fillField('#drink_name', 'Drink 1');
        $I->fillField('#drink_price', '29.90');
        $I->attachFile('#image_preview_input', 'avatar_test.jpeg');
        $I->click('Adicionar');
        $I->see('Imagem registrada com sucesso!');
        $I->seeElement('img[src*="avatar_test"]');

        $I->amOnPage('/admin/drinks/1');
        $I->makeScreenshot('excluir-btn-antes');
        for ($i = 0; $i < 3; $i++) {
            $I->executeJS("window.scrollTo(0, document.body.scrollHeight);");
            $I->wait(0.5);
        }

        $I->makeScreenshot('excluir-btn-depois');
        $I->see('Excluir');
        $I->click('Excluir');
        $I->dontSeeElement('img[src*="avatar_test"]');

        $I->makeScreenshot('foto-excluída');
    }
}
