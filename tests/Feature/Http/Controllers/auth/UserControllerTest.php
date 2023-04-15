<?php

namespace Tests\Feature\Http\Controllers\auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_user(): void
    {
        $data = [
            'name'      => fake()->name(),
            'email'     => fake()->safeEmail(),
            'password'  => 'password123'
        ];
        $response = $this->postJson('api/join', $data);
        $response->assertCreated()->json();
        $this->assertTrue($response['success']);
        $this->assertDatabaseHas('users', [
            'name'  => $data['name'],
            'email' => $data['email']
        ]);
    }

    public function test_can_login(): void
    {
        $user       = User::factory()->create();
        $data       = [
            'email'     => $user->email,
            'password'  => 'password'
        ];
        $response   = $this->postJson('api/login', $data);
        $response->assertOk()->json();
        $this->assertTrue($response['success']);
        $this->assertArrayHasKey('token', $response);
        $this->assertArrayHasKey('data', $response);
    }
}
