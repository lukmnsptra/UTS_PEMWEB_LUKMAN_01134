<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    private array $category = [
        'id' => '',
        'name' => 'Fruits'
    ];

    protected function setUp(): void
    {
        parent::setUp();

        Category::query()->forceDelete();
        User::query()->forceDelete();

        $user = new User();
        $user->name = 'Taufik';
        $user->email = 'KQnJp@example.com';
        $user->role = 'admin';
        $user->password = Hash::make('rahasia1234');
        $user->save();

        $this->actingAs($user);
    }

    public function testCreateSuccess(): void
    {
        $response = $this
            ->post('/api/categories', [
                'name' => $this->category['name']
            ])
            ->assertOk()
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'name',
                    'created_at',
                    'updated_at'
                ]
            ])
            ->getOriginalContent();

        $this->category = $response['data'];
    }

    public function testCreateFailed(): void
    {
        $this
            ->post('/api/categories', [
                'name' => ''
            ])
            ->assertStatus(400)
            ->assertJsonStructure([
                'success',
                'errors' => [
                    'name'
                ]
            ]);
    }

    public function testDeleteSuccess(): void
    {
        $this->testCreateSuccess();
        $this
            ->delete('/api/categories/' . $this->category['id'])
            ->assertOk()
            ->assertJsonStructure([
                'success'
            ]);
    }

    public function testDeleteFailed(): void
    {
        $this
            ->delete('/api/categories/0')
            ->assertStatus(400)
            ->assertJsonStructure([
                'success',
                'errors' => [
                    'message'
                ]
            ]);
    }

    public function testUpdateSuccess(): void
    {
        $this->testCreateSuccess();
        $this
            ->put('/api/categories/' . $this->category['id'], [
                'name' => 'Vegetables'
            ])
            ->assertOk()
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'name',
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'name' => 'Vegetables'
                ]
            ]);
    }
}
