<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Category::query()->forceDelete();
        Product::query()->forceDelete();
    }

    private function createCategory(): Category
    {
        return Category::create([
            'name' => 'Kiloan'
        ]);
    }

    public function testCreate(): Product
    {
        $category = $this->createCategory();
        $product = Product::create([
            'name' => 'Jaket Kiloan',
            'description' => 'Jaket Kiloan',
            'category_id' => $category->id,
            'price' => 10000,
            'image' => 'https://example.com/image.png',
            'expired_at' => Carbon::now(),
            'modified_by' => 'admin@example.com'
        ]);

        self::assertEquals('Jaket Kiloan', $product->name);

        return $product;
    }

    public function testDeleteCategory(): void
    {
        $category = Category::create([
            'name' => 'Kiloan'
        ]);

        $product = Product::create([
            'name' => 'Jaket Kiloan',
            'description' => 'Jaket Kiloan',
            'category_id' => $category->id,
            'price' => 10000,
            'image' => 'https://example.com/image.png',
            'expired_at' => Carbon::now(),
            'modified_by' => 'admin@example.com'
        ]);

        $category->delete();

        self::assertNull(Product::find($product->id));
    }

    public function testGet(): void
    {
        $product = $this->testCreate();
        self::assertNotNull(Product::find($product->id));
    }

    public function testDelete(): void
    {
        $product = $this->testCreate();
        $category = $product->category;

        $product->delete();

        self::assertNull(Product::find($product->id));
        self::assertNotNull($category->id);
    }

    public function testUpdate(): void
    {
        $product = $this->testCreate();
        $product->name = 'Jaket';
        $product->update();

        self::assertEquals('Jaket', Product::find($product->id)->name);
    }

    public function testGetCategory(): void
    {
        $product = $this->testCreate();
        self::assertNotNull($product->category);
    }

    public function testGetProducts(): void
    {
        $category = Category::create([
            'name' => 'Kiloan'
        ]);

        Product::factory(10)->create(function () use ($category) {
            return [
                'name' => fake()->name(),
                'description' => fake()->sentence(),
                'category_id' => $category->id,
                'price' => fake()->numberBetween(10000, 100000),
                'image' => 'https://example.com/image.png',
                'expired_at' => Carbon::now(),
                'modified_by' => fake()->email()
            ];
        });

        self::assertEquals(10, $category->products()->count());
    }
}
