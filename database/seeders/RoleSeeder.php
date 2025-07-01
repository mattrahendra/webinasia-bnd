<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);

        // Assign roles to users
        $admin = User::first();
        if ($admin) {
            $admin->assignRole('admin');
        }

        // Optionally assign 'user' role to other users
        User::where('id', '!=', $admin?->id)->get()->each(function ($user) {
            $user->assignRole('user');
        });
    }
}
