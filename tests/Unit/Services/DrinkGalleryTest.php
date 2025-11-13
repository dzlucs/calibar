<?php

namespace Tests\Unit\Services;

use App\Models\Drink;
use App\Services\DrinkGallery;
use Tests\TestCase;

class DrinkGalleryTest extends TestCase
{
    public function testValidImageUpload()
    {
        $drink = new Drink(['name' => 'Teste', 'price' => '20']);
        $gallery = new DrinkGallery($drink, [
            'extension' => ['jpg', 'jpeg', 'png'],
            'size' => 2 * 1024 * 1024,
        ]);

        $image = [
            'name' => 'teste.jpg',
            'tmp_name' => __DIR__ . '/../../files/avatar_test.jpg',
            'size' => 150000,
            'type' => 'image/jpeg',
        ];

        $this->assertTrue($gallery->create($image));
    }

    public function testInvalidExtension()
    {
        $drink = new Drink(['name' => 'Teste', 'price' => '10']);
        $gallery = new DrinkGallery($drink, [
            'extension' => ['jpg', 'png'],
            'size' => 2 * 1024 *1024,
        ]);

        $image = [
            'name' => 'script.exe',
            'tmp_name' => '/tmp/script.exe',
            'size' => 50000,
            'type' => 'application/octet-stream',
        ];

        $this->assertFalse($gallery->create($image));
    }

    public function testImageTooLarge()
    {
        $drink = new Drink(['name' => 'Teste', 'price' => '30']);
        $gallery = new DrinkGallery($drink, [
            'extension' => ['jpg', 'png'],
            'size' => 2 * 1024 *1024,
        ]);

        $image = [
            'name' => 'big.jpg',
            'tmp_name' => '/tmp/big.jpg',
            'size' => 5 * 1024 * 1024,
            'type' => 'image/jpg',
        ];

        $this->assertFalse($gallery->create($image));
    }

    public function testCanViewUploadedImage()
    {
        $drink = Drink::create(['name' => 'Caipirinha', 'price' => 15]);
        $image = $drink->images()->create(['image_name' => 'foto.jpg']);

        // requisição para visualizar as imagens do drink
        $response = $this->get("/drinks/{$drink-id}/images");

        $response->assertStatus(200);
        $response->assertSee('foto.jpg');
    }
}