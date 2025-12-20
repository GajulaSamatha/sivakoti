<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create the Super Admin User
        User::updateOrCreate(
            ['email' => 'samathagajula22@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'), // You can change this default password
            ]
        );

        // Run the Roles and Permissions Seeder
        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);
    }
}
