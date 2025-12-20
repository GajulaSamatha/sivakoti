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

        // Find the specific user by email
        $user = User::where('email', 'samathagajula22@gmail.com')->first();

        if ($user) {
            $user->assignRole($superadminRole);
            $this->command->info('Superadmin role assigned to samathagajula22@gmail.com');
        } else {
            $this->command->error('User samathagajula22@gmail.com not found.');
        }
    }
}