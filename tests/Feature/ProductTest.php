<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product; // Import the model Product


class ProductTest extends TestCase
{

    use RefreshDatabase; // Clean and migrate the database before each test

    /**
     * A basic feature test example.
     */
    public function test_index_returns_all_products()
    {
        // Crea 5 productos de prueba
        Product::factory()->count(5)->create();

        // Realiza la petición al endpoint
        $response = $this->getJson('/api/products');

        // Verifica el estado HTTP y la cantidad de productos en la respuesta
        $response->assertStatus(200)
                 ->assertJsonCount(5, 'data');
    }
}
