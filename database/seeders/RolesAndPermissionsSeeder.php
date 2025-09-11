<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User; // Make sure to import the User model

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if the 'superadmin' role already exists to prevent duplicates
        $superadminRole = Role::firstOrCreate(['name' => 'superadmin']);

        // Find the first user and assign the superadmin role
        $user = User::find(1); // Adjust this if your superadmin user is not the first one
        if ($user) {
            $user->assignRole($superadminRole);
            $this->command->info('Superadmin role created and assigned to User ID: 1');
        } else {
            $this->command->error('User with ID 1 not found. Please create a user first.');
        }
    }
}