<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear cache to avoid permission issues
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $records = Role::firstOrCreate(['name' => 'records']);
        $oed = Role::firstOrCreate(['name' => 'oed']);
        $user = Role::firstOrCreate(['name' => 'users']);
    }
}
