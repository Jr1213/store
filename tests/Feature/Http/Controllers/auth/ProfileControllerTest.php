<?php

namespace Tests\Feature\Http\Controllers\auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_profile(): void
    {
        $response   = $this->getJson('api/profile', $this->header);
        $response->assertOk()->json();
        $this->assertArrayHasKey('data', $response);
        $response->assertJsonFragment([
            'name'  => $this->user->name,
            'email' => $this->user->email,
            'id'    => $this->user->id
        ]);
        $this->assertTrue($response['success']);
    }

    public function test_can_update_profile(): void
    {
        $data = [
            'name'  => fake()->name(),
            'email' => fake()->safeEmail()
        ];
        $response = $this->patchJson('api/profile/' . $this->user->id, $data, $this->header);
        $response->assertOk()->json();
        $this->assertArrayHasKey('data', $response);
        $response->assertJsonFragment([
            'name'  => $data['name'],
            'email' => $data['email'],
            'id'    => $this->user->id
        ]);
        $this->assertDatabaseHas('users', [
            'name'  => $data['name'],
            'email' => $data['email']
        ]);
        $this->assertTrue($response['success']);
    }

    public function test_can_delete_user(): void
    {
        $response = $this->deleteJson('api/profile/' . $this->user->id, [], $this->header);
        $response->assertNoContent();
        $this->assertDatabaseMissing('users', [
            'id'            => $this->user->id,
            'deleted_at'    => NULL
        ]);
    }

    public function test_can_change_password(): void
    {
        $data   =  [
            'password_old'  => 'password',
            'password'      => 'password123'
        ];

        $response = $this->postJson('api/profile', $data, $this->header);
        $response->assertOk()->json();
        $this->assertTrue($response['success']);
    }
}
