<?php

namespace Tests\Acceptance\Authentication;

use App\Models\Admin;
use App\Models\User;
use Tests\Acceptance\BaseAcceptanceCest;
use Tests\Support\AcceptanceTester;

class LoginCest extends BaseAcceptanceCest
{
    public function loginSuccessfully(AcceptanceTester $page): void
    {
        $user = new User([
            'name' => 'Admin Test',
            'email' => 'admin@example.com',
            'password' => 'password123',
            'encrypted_password' => 'password123'
        ]);
        $user->save();
        (new Admin([
            'user_id' => $user->id
        ]))->save();

        $page->amOnPage('/login');

        $page->fillField('user[email]', $user->email);
        $page->fillField('user[password]', $user->password);

        $page->click('Fazer login');

        $page->see('Login realizado com sucesso!');
        $page->seeInCurrentUrl('/admin');
    }

    public function loginUnsuccessfully(AcceptanceTester $page): void
    {
        $page->amOnPage('/login');

        $page->fillField('user[email]', 'admin@example');
        $page->fillField('user[password]', 'wrong_password');

        $page->click('Fazer login');

        $page->see('Email e/ou senha inválidos!');
        $page->seeInCurrentUrl('/login');
    }
}
