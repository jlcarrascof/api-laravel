<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product; // Import the model Product
use App\Models\Category; // Import the model Category


class ProductTest extends TestCase
{

    use RefreshDatabase; // Clean and migrate the database before each test

    /**
     * A basic feature test example.
     */
    public function test_index_returns_all_products()
    {
        // Create 5 test products
        Product::factory()->count(5)->create();

        // Making the request to the endpoint
        $response = $this->getJson('/api/products');

        // Check the HTTP status and the quantity of the response products.
        $response->assertStatus(200)
                 ->assertJsonCount(5, 'data');
    }

    public function test_store_creates_a_product()
    {

        Category::factory()->create(['id' => 1]);

        // Test data for the product
        $productData = [
            'name' => 'Test Product',
            'description' => 'Description of Test product',
            'price' => 66.66,
            'stock' => 40,
            'category_id' => 1,
        ];

        // Making the request to the endpoint

        $response = $this->postJson('/api/products', $productData);

        // Check the HTTP status
        $response->assertStatus(201)
        ->assertJsonPath('data.name', 'Test Product');
    }
}
