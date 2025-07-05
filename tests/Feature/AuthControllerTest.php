<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{

    use RefreshDatabase;

    public function test_can_user_register_and_access_protected_route()
    {
        $response = $this->postJson('/api/v1/register', [
            'name' => 'John Doe',
            'email' => 'TJr1o@example.com',
            'password' => 'password11',
        ]);

        $response->assertStatus(200);

        $responseData = $response->json();
        $token = $responseData['data']['token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/v1/protected-route');

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'email' => 'TJr1o@example.com'
        ]);
    }

    public function test_can_user_login_and_access_protected_route()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);

        $responseData = $response->json();
        $token = $responseData['data']['token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/v1/protected-route');

        $response->assertStatus(200);
    }
}
