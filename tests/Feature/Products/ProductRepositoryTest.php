<?php

namespace Tests\Feature\Products;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Repositories\Products\ProductRepository;

class ProductRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $productRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->productRepository = new ProductRepository();
    }

    public function testGetAllProducts()
    {
        // Create some test products
        $product1 = Product::factory()->create(['name' => 'Product 1', 'price' => 100]);
        $product2 = Product::factory()->create(['name' => 'Product 2', 'price' => 150]);

        // Simulate a request with filters
        $request = (object) [
            'filters' => json_encode(['name' => 'Product 1', 'min_price' => 100])
        ];

        $result = $this->productRepository->all($request);

        $this->assertCount(1, $result);
        $this->assertEquals($product1->name, $result[0]->name);
    }

    public function testGetProductById()
    {
        $product = Product::factory()->create(['name' => 'Test Product']);

        $result = $this->productRepository->find($product->id);

        $this->assertEquals($product->name, $result->name);
    }

    public function testCreateProduct()
    {
        $productData = [
            'name' => 'New Product',
            'price' => 200,
            'quantity' => 10,
        ];

        $createdProduct = $this->productRepository->create($productData);

        $this->assertDatabaseHas('products', $productData);
        $this->assertInstanceOf(Product::class, $createdProduct);
    }

    public function testUpdateProduct()
    {
        $product = Product::factory()->create();

        $updatedData = [
            'name' => 'Updated Product',
            'price' => 250,
            'quantity' => 5,
        ];

        $this->productRepository->update($updatedData, $product->id);

        $this->assertDatabaseHas('products', $updatedData);
    }

    public function testDeleteProduct()
    {
        $product = Product::factory()->create();

        $this->productRepository->delete($product->id);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    // Add more test methods if needed

    // ...
}
