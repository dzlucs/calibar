<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsTo;
use Core\Database\ActiveRecord\Model;
use App\Services\DrinkGallery;

/**
 * @property int $id
 * @property int $drink_id
 * @property string $name
 */

class DrinkImage extends Model
{
    protected static string $table = 'drink_images';
    protected static array $columns = [
        'drink_id',
        'image_name'
    ];

    public function drink(): BelongsTo
    {
        return $this->belongsTo(Drink::class, 'drink_id');
    }

/*     public function gallery(): DrinkGallery
    {
        return new DrinkGallery($this, ['extension' => ['png', 'jpg', 'jpeg'], 'size' => 2 * 1024 * 1024]);
    } */
}
