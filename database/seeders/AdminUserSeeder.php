<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run()
    {
        // Create an admin role if it does not exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $recordsRole = Role::firstOrCreate(['name' => 'records']);
        $oedRole = Role::firstOrCreate(['name' => 'oed']);

        // Create an admin user
        $userAdmin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'], // Use a unique identifier (e.g., email)
            [
                'name' => 'Admin',
                'password' => bcrypt('password') // Use a secure password
            ]
        );

        // Assign the admin role to the user
        $userAdmin->assignRole($adminRole);

        // Create an admin for records
        $userAdminRecords = User::updateOrCreate(
            ['email' => 'records-admin@gmail.com'], // Use a unique identifier (e.g., email)
            [
                'name' => 'records-admin',
                'password' => bcrypt('password') // Use a secure password
            ]
        );

        // Assign the admin role to the user
        $userAdminRecords->assignRole($adminRole);

        // Create Records User
        $userRecords = User::updateOrCreate(
            ['email' => 'records@pcc.gov.ph'],
            [
                'name' => 'Records',
                'password' => bcrypt('password')
            ]
        );
        $userRecords->syncRoles([$adminRole, $recordsRole]);

        // Create OED User
        $userOed = User::updateOrCreate(
            ['email' => 'oed@pcc.gov.ph'],
            [
                'name' => 'OED',
                'password' => bcrypt('password')
            ]
        );
        $userOed->assignRole($oedRole);
    }
}
