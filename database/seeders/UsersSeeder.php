<?php

namespace Database\Seeders;

use App\Enums\RolesEnum;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create an admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        // give them the admin role
        $admin->assignRole(RolesEnum::Admin);

        // create 10 users and give them all the user role
        for ($i = 0; $i < 10; $i++) {
            $user = \App\Models\User::factory()->create();
            $user->assignRole(RolesEnum::Admin);
        }
    }
}
