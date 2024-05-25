<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'user_id',
        'description',
        'quantity',
        'price',
    ];



    /**
     * Get the images associated with the product.
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}
