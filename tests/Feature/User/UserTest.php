<?php

namespace Tests\Feature\User;

use App\Actions\User\CreateUserAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_user_policy_blocks_under_levelled_users() : void
    {
        // this user should only have a 'user' role
        $user = User::find(4);

        // create the users token
        $token = $user->createToken('test')->plainTextToken;

        // send the request
        $response = $this->actingAs($user)
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ])
                ->get('/api/users');

        // assert forbidden
        $response->assertStatus(403);
    }

    public function test_user_policy_allows_admin_user_to_access_route() : void
    {
        // this user should only have a 'user' role
        $user = User::find(1);

        // create the users token
        $token = $user->createToken('test')->plainTextToken;

        // send the request
        $response = $this->actingAs($user)
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ])
                ->get('/api/users');

        // assert forbidden
        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Admin User']);
    }
}
