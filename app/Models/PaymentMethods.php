<?php

namespace App\Models;

use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 */
class PaymentMethod extends Model
{
    protected static string $table = 'payment_methods';
    protected static array $columns = ['name', 'description'];

    public function validates(): void
    {
        // Verifica se o nome está preenchido
        if (empty($this->name)) {
            $this->addError('name', 'O método de pagamento é obrigatório.');
        }

        /* // Verifica se o preço está preenchido e é positivo
        if (empty($this->price) || $this->price <= 0) {
            $this->addError('price', 'O preço deve ser maior que zero.');
        } */
    }
}