<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsTo;
use Core\Database\ActiveRecord\HasMany;
use Core\Database\ActiveRecord\Model;
use Lib\Validations;

/**
 * @property int $id
 * @property string $name
 * @property string $price
 * @property int $admin_id
 *
 */

class Drink extends Model
{
    protected static string $table = 'drinks';
    protected static array $columns = [
        'name',
        'price',
        'admin_id'
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function validates(): void
    {
        Validations::notEmpty('name', $this);
        Validations::notEmpty('price', $this);
        $this->adminExists();
    }

    //VERIFICAR NECESSIDADE DO MÉTODO
    public function adminExists(): bool
    {
        if (Admin::exists(['admin_id', $this->admin_id])) {
            return true;
        }

        $this->addError('admin_id', 'does not exist!');
        return false;
    }

    //VERIFICAR MÉTODOS COM COMENTÁRIO NA PR**
/*     public function addError(string $attribute, string $message): void
    {
        $this->errors[$attribute] = "{$attribute} {$message}";
    } */

    /**
    *@return string[] List of error messages, each as a string.
    */

    public function getErrors(): array
    {
        return $this->errors;
    }
}
