<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_can_register()
    {
        $response = $this->post('/api/register', [
           'name' => 'Test User',
           'email' => 'test@example.com',
           'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertCreated();
    }

    public function test_new_users_can_login() : void
    {
        $user = User::factory()->create();

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['login' => true]);
    }

    public function test_authentication_middleware_blocks_bad_requests() : void
    {
        /*
         * send a request to /api/user without a valid token
         * this should tell us off because we're not logged in correctly
         * */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer invalid-token',
            'Accept' => 'application/json',
        ])
            ->get('/api/user');

        $response->assertStatus(401);
    }
}
