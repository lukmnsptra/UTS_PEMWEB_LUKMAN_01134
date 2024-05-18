<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Category::query()->forceDelete();
    }

    public function testCreate(): Category
    {
        $category = Category::create([
            'name' => 'Fruits'
        ]);

        self::assertEquals('Fruits', $category->name);

        return $category;
    }

    public function testDelete(): void
    {
        $category = $this->testCreate();
        $category->delete();

        self::assertNull(Category::find($category->id));
    }

    public function testUpdate(): void
    {
        $category = $this->testCreate();
        $category->name = 'Foods';
        $category->update();

        self::assertEquals('Foods', Category::find($category->id)->name);
    }

    public function testGet(): void
    {
        $category = $this->testCreate();

        self::assertNotNull(Category::find($category->id));
    }
}
