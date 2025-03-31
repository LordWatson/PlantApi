<?php

namespace Plant;

use App\Actions\User\CreateUserAction;
use App\Enums\RolesEnum;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PlantTest extends TestCase
{
    private function authenticate(User $user)
    {
        $token = $user->createToken('test')->plainTextToken;

        return [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
    }

    public function test_a_user_can_list_plants() : void
    {
        // get the user
        $user = User::find(1);

        $headers = $this->authenticate($user);

        // send the request
        $response = $this->actingAs($user)
            ->withHeaders($headers)
            ->get('/api/plants');

        $response->assertStatus(200)
            ->assertJsonFragment(['common_name' => 'European Silver Fir'])
            ->assertJsonFragment(['scientific_name' => 'Abies alba']);
    }

    public function test_a_user_can_query_the_plant_list() : void
    {
        // get the user
        $user = User::find(1);

        $headers = $this->authenticate($user);

        // send the request
        $response = $this->actingAs($user)
            ->withHeaders($headers)
            ->get('/api/plants?q=monst');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => 3184,
                'common_name' => 'cranesbill',
            ])
            ->assertJsonFragment([
                'id' => 5257,
                'common_name' => 'Swiss cheese plant',
                'scientific_name' => 'Monstera deliciosa',
            ]);
    }

    public function test_a_user_can_view_a_plants_details() : void
    {
        // get the user
        $user = User::find(1);

        $headers = $this->authenticate($user);

        // send the request
        $response = $this->actingAs($user)
            ->withHeaders($headers)
            ->get('/api/plants/1012');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => 1012,
                'common_name' => 'Swamp milkweed',
                'scientific_name' => 'Asclepias incarnata \'Soulmate\'',
                'watering' => 'Frequent',
                'watering_unit' => 'Days',
                'sunlight' => [
                    'Sun'
                ],
            ]);
    }
}
