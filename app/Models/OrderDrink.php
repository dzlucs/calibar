<?php

namespace App\Models;

use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property int $order_id   Foreign key for orders(id)
 * @property int $drink_id   Foreign key for drinks(id)
 * @property int $quantity
 * @property float $price
 */
class OrderDrink extends Model
{
    protected static string $table = 'order_drinks';
    protected static array $columns = ['order_id', 'drink_id', 'quantity', 'price'];

    public function validates(): void
    {
        // Validação: Id do pedido é obrigatório
        if (empty($this->order_id)) {
            $this->addError('order_id', 'Id do pedido é obrigatório!');
        }

        // Validação: Id do drink é obrigatório
        if (empty($this->drink_id)) {
            $this->addError('drink_id', 'Id da bebida é obrigatório!');
        }

        // Validação: quantidade obrigatória e maior que zero
        if (empty($this->quantity) || ($this->quantity <= 0)) {
            $this->addError('quantity', 'Quantidade deve ser maior que zero!');
        }

        // Validação: preço obrigatório e não negativo
        if (empty($this->price) || ($this->price < 0)) {
            $this->addError('price', 'Preço deve ser maior ou igual a zero!');
        }
    }
}