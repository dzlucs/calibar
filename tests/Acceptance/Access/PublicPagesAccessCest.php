<?php

namespace Tests\Acceptance\Access;

use App\Models\User;
use App\Models\Admin;
use App\Models\Customer;
use Tests\Acceptance\BaseAcceptanceCest;
use Tests\Support\AcceptanceTester;

class PublicPagesAccessCest extends BaseAcceptanceCest
{
    public function loginPageShouldRedirectAuthenticatedCustomer(AcceptanceTester $I): void
    {
        $user = new User([
            'name' => 'Customer Test',
            'email' => 'customer@example.com',
            'password' => 'password456',
            'encrypted_password' => 'password456'
        ]);
        $user->save();

        $customer = new Customer(['user_id' => $user->id]);
        $customer->save();

        $I->amOnPage('/login');
        $I->fillField('user[email]', $user->email);
        $I->fillField('user[password]', 'password456');
        $I->click('Fazer login');

        $I->seeInCurrentUrl('/customer');
    }

    public function loginPageShouldRedirectAuthenticatedAdmin(AcceptanceTester $I): void
    {
        $user = new User([
            'name' => 'Admin Test',
            'email' => 'admin@example.com',
            'password' => 'password789',
            'encrypted_password' => 'password789'
        ]);
        $user->save();
        (new Admin([
            'user_id' => $user->id
        ]))->save();

        $I->amOnPage('/login');
        $I->fillField('user[email]', $user->email);
        $I->fillField('user[password]', 'password789');
        $I->click('Fazer login');

        $I->seeInCurrentUrl('/admin');
    }
}
