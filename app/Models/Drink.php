<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsTo;
use Core\Database\ActiveRecord\HasMany;
use Core\Database\ActiveRecord\Model;
use Lib\Validations;
use App\Services\DrinkGallery;

/**
 * @property int $id
 * @property string $name
 * @property string $price
 * @property int $admin_id
 * @property \App\Models\DrinkImage[] $images
 *
 */

class Drink extends Model
{
    protected static string $table = 'drinks';
    protected static array $columns = [
        'name',
        'price',
        'admin_id',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(DrinkImage::class, 'drink_id');
    }

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
        if (Admin::exists(['id' => $this->admin_id])) {
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

    public function gallery(): DrinkGallery
    {
        return new DrinkGallery($this, ['extension' => ['png', 'jpg', 'jpeg'], 'size' => 2 * 1024 * 1024]);
    }

/*     public function save() {

        super::save();
        $this->gallery()->create($image)
    } */
}
