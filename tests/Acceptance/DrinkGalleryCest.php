<?php

namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class DrinkGalleryCest extends BaseAcceptanceCest
{

    public function testUploadValidImage(AcceptanceTester $I)
    {
        $I->amOnPage('/drinks');
        $I->attachFile('input[name=image]', 'avatar_test.jpg');
        $I->click('Upload');
        $I->see('Imagem enviada com sucesso');
        $I->seeFileFound('avatar_test.jpg', 'public/uploads/drinks/');
    }

    public function testRemoveImage(AcceptanceTester $I)
    {
        $I->amOnPage('/drinks/1/gallery');
        $I->click('Remover imagem');
        $I->dontSeeFileFound('avatar_test.jpg', 'public/uploads/drinks');
    }

    public function testInvalidUpload(AcceptanceTester $I)
    {
        $I->amOnPage('/drinks');
        $I->attachFile('input[name=image]', 'evil.exe');
        $I->click('Salvar');
        $I->see('Cervejas artesanais');
    }
}