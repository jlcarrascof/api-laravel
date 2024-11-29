<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
