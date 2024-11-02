<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Producto de Ejemplo',
            'description' => 'Descripción del producto de ejemplo.',
            'price' => 99.99,
            'stock' => 10,
            'category_id' => 1,
        ]);

        Product::factory(10)->create(); // Generate 10 random products
    }
}
