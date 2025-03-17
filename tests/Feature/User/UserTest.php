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

    public function test_a_user_can_be_viewed() : void
    {
        // get the user
        $user = User::find(1, ['id', 'name', 'email']);

        // create the users token
        $token = $user->createToken('test')->plainTextToken;

        // send the request
        $response = $this->actingAs($user)
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ])
            ->get('/api/users/1');

        // check response status and content
        $response->assertStatus(200)
            ->assertJson([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]);
    }
}
