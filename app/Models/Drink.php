<?php

namespace App\Models;

use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property string $name
 * @property float $price
 * @property string $description
 * @property string $image
 */
class Drink extends Model
{
    protected static string $table = 'drinks';
    protected static array $columns = ['name', 'price', 'description', 'image'];

    public function validates(): void
    {
        // Verifica se o nome está preenchido
        if (empty($this->name)) {
            $this->addError('name', 'O nome da bebida é obrigatório.');
        }

        // Verifica se o preço está preenchido e é positivo
        if (empty($this->price) || $this->price <= 0) {
            $this->addError('price', 'O preço deve ser maior que zero.');
        }
    }
}