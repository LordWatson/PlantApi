<?php

namespace Tests\Feature\User;

use App\Actions\User\CreateUserAction;
use App\Enums\RolesEnum;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    private function authenticate(User $user)
    {
        $token = $user->createToken('test')->plainTextToken;

        return [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
    }

    public function test_user_policy_blocks_under_levelled_users() : void
    {
        // this user should only have a 'user' role
        $user = User::find(4);

        $headers = $this->authenticate($user);

        // send the request
        $response = $this->actingAs($user)
            ->withHeaders($headers)
            ->get('/api/users');

        // assert forbidden
        $response->assertStatus(403);

        // checks an activity log is created
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'message' => 'Access Denied',
        ]);
    }

    public function test_user_policy_allows_admin_user_to_access_route() : void
    {
        // this user should only have a 'user' role
        $user = User::find(1);

        $headers = $this->authenticate($user);

        // send the request
        $response = $this->actingAs($user)
            ->withHeaders($headers)
            ->get('/api/users');

        // assert forbidden
        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Admin User']);
        $response->assertJsonFragment(['role' => RolesEnum::Admin]);
    }

    public function test_a_user_can_be_viewed() : void
    {
        // get the user
        $user = User::find(1, ['id', 'name', 'email']);

        $headers = $this->authenticate($user);

        // send the request
        $response = $this->actingAs($user)
            ->withHeaders($headers)
            ->get('/api/users/1');

        // check response status and content
        $response->assertStatus(200)
            ->assertJson([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]);
    }

    public function test_a_user_can_be_updated()
    {
        // get the user
        $user = User::find(1, ['id', 'name', 'email']);

        $headers = $this->authenticate($user);

        // send the request
        $response = $this->actingAs($user)
            ->withHeaders($headers)
            ->put('/api/users/2',[
                'name' => 'Updated Name',
                'email' => 'updated@email.com',
            ]);

        // check response status and content
        $response->assertStatus(200)
            ->assertJson([
                'updated' => [
                    'name' => 'Updated Name',
                    'email' => 'updated@email.com',
                ]
            ]);

        // checks an activity log is created
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'event' => 'Updated',
            'method' => 'PUT',
        ]);
    }

    public function test_a_user_can_be_deleted()
    {
        // make a user to be deleted
        $testUser = User::factory()->create();

        // get an admin user that will do the deleting
        $adminUser = User::find(1);

        $headers = $this->authenticate($adminUser);

        // send the request
        $response = $this->actingAs($adminUser)
            ->withHeaders($headers)
            ->delete('/api/users/' . $testUser->id);

        // check the response
        $response->assertStatus(200);

        // check the user has been deleted
        $this->assertDatabaseMissing('users', [
            'id' => $testUser->id,
        ]);

        // check an activity log has been created
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $adminUser->id,
            'event' => 'Deleted',
            'method' => 'DELETE',
        ]);
    }

    public function test_unauthorized_user_cannot_delete_user()
    {
        // make a user that should not be able to delete another
        $unauthorizedUser = User::factory()->create();

        // give them a role
        $unauthorizedUser->assignRole(RolesEnum::User);

        // make a user that we're going to try to delete
        $testUser = User::factory()->create();

        $headers = $this->authenticate($unauthorizedUser);

        // send the request
        $response = $this->actingAs($unauthorizedUser)
            ->withHeaders($headers)
            ->delete('/api/users/' . $testUser->id);

        // check the status
        $response->assertStatus(403);

        // check the user still exists
        $this->assertDatabaseHas('users', [
            'id' => $testUser->id,
        ]);
    }
}
