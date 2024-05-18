<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use App\Services\JwtService;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    private JwtService $jwtService;
    private array $credentials = [
        'name' => 'Taufik',
        'email' => 'test@example.com',
        'password' => 'rahasia1234'
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->jwtService = $this->app->make(JwtService::class);
        User::query()->forceDelete();
    }

    public function testRegisterSuccess(): void
    {
        $this
            ->post('/api/auth/register', [
                'name' => $this->credentials['name'],
                'email' => $this->credentials['email'],
                'password' => $this->credentials['password']
            ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ]
            ])
            ->assertJson([
                'success' => true
            ]);
    }

    public function testRegisterFailed(): void
    {
        $this
            ->post('/api/auth/register')
            ->assertStatus(400)
            ->assertJson([
                'success' => false,
                'errors' => [
                    'name' => ['The name field is required.'],
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                ]
            ]);
    }

    public function testLoginSuccess(): void
    {
        $this->testRegisterSuccess();

        $this
            ->post('/api/auth/login', [
                'email' => $this->credentials['email'],
                'password' => $this->credentials['password']
            ])
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);
    }

    public function testLoginFailed(): void
    {
        $this
            ->post('/api/auth/login')
            ->assertStatus(400)
            ->assertJson([
                'success' => false,
            ]);
    }
}
