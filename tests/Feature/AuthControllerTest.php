<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user registration.
     */
    public function test_user_can_register()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson(route('register'), $userData);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'user' => ['id', 'name', 'email']
            ]
        ]);
    }

    /**
     * Test user login.
     */
    public function test_user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'laravel-api'),
        ]);

        $response = $this->postJson(route('login'), [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                'access_token'
            ]
        ]);
    }

    public function test_user_cannot_login_with_incorrect_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson(route('login'), [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Invalid credentials.']);
    }
}
