<?php

namespace Tests\Feature\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        User::query()->forceDelete();
    }

    public function testCreate(): User
    {
        $user = User::factory()->create();

        self::assertNotNull($user->id);

        return $user;
    }

    public function testGet(): void
    {
        $user = $this->testCreate();
        self::assertNotNull(User::find($user->id));
    }

    public function testUpdate(): void
    {
        $user = $this->testCreate();
        $user->update([
            'name' => 'Foo Bar',
        ]);

        self::assertEquals('Foo Bar', $user->name);
    }

    public function testDelete(): void
    {
        $user = $this->testCreate();
        $user->delete();

        self::assertNull(User::find($user->id));
    }
}
