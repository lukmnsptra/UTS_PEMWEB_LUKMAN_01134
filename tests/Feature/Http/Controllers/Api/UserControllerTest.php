<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    private array $credentials = [
        'name' => 'taufik',
        'email' => 'taufik@gmail.com',
        'password' => 'rahasia1234',
        'token' => ''
    ];

    protected function setUp(): void
    {
        parent::setUp();
        User::where('id', '!=', 1)->delete();
    }

    private function register(): void
    {
        $this->postJson('/api/auth/register', $this->credentials)->assertStatus(200);
    }

    private function login(): void
    {
        $response = $this
            ->post('/api/auth/login', [
                'email' => $this->credentials['email'],
                'password' => $this->credentials['password']
            ])
            ->assertStatus(200)
            ->assertJsonStructure(['data' => ['token']])
            ->getOriginalContent();

        $this->credentials['token'] = $response['data']['token'];
    }

    public function testCurrentSuccess(): void
    {
        $this->register();
        $this->login();

        $this
            ->get('/api/users/current', [
                'Authorization' => 'Bearer ' . $this->credentials['token']
            ])
            ->assertStatus(200)
            ->assertJsonStructure(['data' => ['id', 'name', 'email']]);
    }

    public function testCurrentFailed(): void
    {
        $this
            ->get('/api/users/current', [
                'Authorization' => 'Bearer ' . $this->credentials['token'] . 'salah'
            ])
            ->assertStatus(401);
    }
}
