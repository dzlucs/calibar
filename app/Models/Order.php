<?php

namespace App\Models;

use Lib\Validations;
use Core\Database\ActiveRecord\Model;
/**
 * @property int $id
 * @property int $user_id   Foreign key to users(id)
 * @property string $status
 * @property float $total_price
 * @property string $payment_method_id Foreign key to payment_methods(id)
 */
class Order extends Model
{
    protected static string $table = 'orders';
    protected static array $columns = ['user_id', 'status', 'status', 'total_price', 'payment_method_id'];

    public function validates(): void
    {
        // Validação: usuário é obrigatório
        if (empty($this->user_id)) {
            $this->addError('user_id', 'Usuário é obrigatório!');
        }

        // Validação: status é obrigatório
        if (empty($this->status)) {
            $this->addError('status', 'Status é obrigatório!');
        }

        // Validação: preço total é obrigatório
        if (empty($this->total_price) || ($this->total_price < 0)) {
            $this->addError('total_price', 'Preço total deve ser maior ou igual a 0!');
        }

        // Validação método de pagamento é obrigatório
        if(empty($this->payment_method_id)) {
            $this->addError('payment_method_id', 'Método de pagamento é obrigatório!');
        }

    }

    /* public function authenticate(string $password): bool
    {
        if ($this->encrypted_password == null) {
            return false;
        }

        return password_verify($password, $this->encrypted_password);
    }

    public static function findByEmail(string $email): User | null
    {
        return User::findBy(['email' => $email]);
    }

    public function __set(string $property, mixed $value): void
    {
        parent::__set($property, $value);

        if (
            $property === 'password' &&
            $this->newRecord() &&
            $value !== null && $value !== ''
        ) {
            $this->encrypted_password = password_hash($value, PASSWORD_DEFAULT);
        }
    } */
}
