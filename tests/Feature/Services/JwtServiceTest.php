<?php

namespace Tests\Feature\Services;

use App\Services\JwtService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class JwtServiceTest extends TestCase
{
    private JwtService $jwtService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->jwtService = $this->app->make(JwtService::class);
    }

    public function testEncode(): string
    {
        $payload = [
            'message' => 'hello world'
        ];
        $token = $this->jwtService->encode($payload);

        self::assertNotNull($token);

        return $token;
    }

    public function testDecode(): void
    {
        $token = $this->testEncode();
        $data = $this->jwtService->decode($token);

        self::assertEquals($data->message, 'hello world');
    }

    public function testCheck(): void
    {
        $token = $this->testEncode();
        $result = $this->jwtService->check($token);

        self::assertTrue($result);
    }

    public function testCheckFailed(): void
    {
        $token = $this->testEncode();
        $token .= 'a';
        $result = $this->jwtService->check($token);

        self::assertFalse($result);
    }

    public function testArrayMulti(): void
    {
        $payload = [
            'user' => [
                'id' => 1
            ]
        ];
        $token = $this->jwtService->encode($payload);
        $data = $this->jwtService->decode($token);

        self::assertEquals($data->user->id, 1);
    }
}
