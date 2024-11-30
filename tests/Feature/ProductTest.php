<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product; // Import the model Product
use App\Models\Category; // Import the model Category
use Illuminate\Foundation\Testing\DatabaseMigrations;


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

    public function test_show_returns_a_product()
    {
        // Crea a test product
        $product = Product::factory()->create();

        // Make the request to the endpoint to get the product
        $response = $this->getJson("/api/products/{$product->id}");

        // Verify HTTP status and the data returned
        $response->assertStatus(200)
                ->assertJsonPath('data.id', $product->id);
    }

    public function test_update_modifies_a_product()
    {
        // Create a test product...

        $product = Product::factory()->create(
            [
                'name' => 'Original Product',
                'price' => 100.00,
        ]);

        // Updated data for a product ..

        $updatedData = [
            'name' => 'Updated Original Product',
            'price' => 150.75,
        ];

        // Make the request endpoint PUT ...

        $response = $this->putJson("/api/products/{$product->id}", $updatedData);

        // Verify HTTP status and the updated data

        $response->assertStatus(200)
                ->assertJsonPath('data.name', 'Updated Original Product')
                ->assertJsonPath('data.price', 150.75);
    }

    public function test_destroy_deletes_a_product()
    {
        // Create a test product
        $product = Product::factory()->create();

        // Make the request to the endpoint to delete the product

    }
}
