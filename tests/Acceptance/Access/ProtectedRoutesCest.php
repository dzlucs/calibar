<?php

namespace Tests\Acceptance\Access;

use Tests\Acceptance\BaseAcceptanceCest;
use Tests\Support\AcceptanceTester;

class ProtectedRoutesCest extends BaseAcceptanceCest
{
    public function guestShouldBeRedirectedFromAdminDashboard(AcceptanceTester $I): void
    {
        $I->amOnPage('/admin');
        $I->seeInCurrentUrl('/login');
        $I->see('Olá, bora pedir um drink?');
    }

    public function guestShouldBeRedirectedFromCustomerDashboard(AcceptanceTester $I): void
    {
        $I->amOnPage('/customer');
        $I->seeInCurrentUrl('/login');
        $I->see('Olá, bora pedir um drink?');
    }
}
