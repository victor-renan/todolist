<?php

namespace Tests\Feature;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    private array $credentials = [
        'email' => 'test@mail.com',
        'password' => 'Test1234',
    ];

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()
            ->create($this->credentials);
    }

    public function test_login_valid(): void
    {
        $response = $this->postJson(
            '/api/auth/login',
            $this->credentials
        );

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'message',
            'token',
            'user' => [
                'remaining_time',
                'name',
                'email'
            ],
        ]);
    }

    public function test_login_invalid_data(): void
    {
        $response = $this->postJson(
            '/api/auth/login',
            array_replace($this->credentials, [
                'password' => 'Invalid123',
            ])
        );

        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);
    }

    public function test_login_invalid_form(): void
    {
        $response = $this->postJson('/api/auth/login', []);
        $response->assertStatus(422);
    }

    public function test_register_valid(): void
    {
        $creds = [
            'name' => 'Test',
            'email' => 'a@a.a',
            'password' => 'Test1234',
            'password_confirmation' => 'Test1234',
        ];

        $response = $this->postJson('/api/auth/register', $creds);

        $response->assertStatus(200);
        $response->assertJsonStructure(['message']);

        $this->credentials = array_replace(
            $this->credentials,
            $creds
        );

        $this->test_login_valid();
    }

    public function test_register_invalid_form(): void
    {
        $creds = [
            'name' => 'Test',
            'email' => $this->credentials['email'],
            'password' => 'Test1234',
        ];

        $response = $this->post('/api/auth/register', $creds);

        $response->assertRedirect();
    }

    public function test_logout(): void
    {
        $this->user->refresh();

        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/auth/logout');

        $response->assertStatus(200);
    }
}
