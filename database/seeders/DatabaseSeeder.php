<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Role;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $userAdmin = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
        ]);

        $role = Role::create(['name' => 'Admin']);
        $userAdmin->assignRole($role);
    }
}
