<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     title="Product",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Product 1"),
 *     @OA\Property(property="description", type="string", example="Product description"),
 *     @OA\Property(property="price", type="number", format="float", example=99.99),
 *     @OA\Property(property="stock", type="integer", example=100),
 *     @OA\Property(property="category_id", type="integer", example=1)
 * )
*/

class Product extends Model
{
    use HasFactory; // Trait for Eloquent ORM

    protected $fillable = [
        'name', 'description', 'price', 'stock', 'category_id'
    ];

    protected $casts = [
        'price' => 'float', // Make sure that price will be always a float.
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
