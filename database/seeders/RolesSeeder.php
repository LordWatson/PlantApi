<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create roles
        Role::create([
            'name' => 'Admin',
            'description' => 'Admin can access all levels and perform all CRUD actions',
            'level' => 9999,
            'created_at' => now(),
            'updated_at' => now(),

        ]);

        Role::create([
            'name' => 'User',
            'description' => 'User can access some levels and perform some CRUD actions on their own resources',
            'level' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
