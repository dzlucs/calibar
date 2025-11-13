<?php

namespace Tests\Unit\Services;

use App\Services\DrinkGalleryService;
use Tests\TestCase;
use Exception;

class DrinkGalleryServiceTest extends TestCase
{
    public function testCannotAddImageToNonexistentDrink()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Drink não encontrado');

        $service = new DrinkGalleryService();

        $fakeFile = [
            'name' => 'invalido.jpg',
            'tmp_name' => __DIR__ . '/../../files/avatar.jpg',
            'size' => 200000,
            'type' => 'image/jpg',
        ];

        $service->uploadImage(9999, $fakeFile);
    }
}