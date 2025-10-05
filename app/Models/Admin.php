<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsTo;
use Core\Database\ActiveRecord\HasMany;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property int $user_id
 *
 *
 * */

class Admin extends Model
{
    protected static string $table = 'admins';
    protected static array $columns = ['user_id'];

    protected array $errors = [];

    //Verifica se o admin está vinculado a um usuário via a coluna user_id
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function validates(): void
    {
        if ($this->user_id == 0) {
            $this->addError('user_id', 'O user_id não pode ser nulo.');
            return;
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
