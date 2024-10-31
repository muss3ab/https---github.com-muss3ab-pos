<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'view sales',
            'create sales',
            'view products',
            'create products',
            'edit products',
            'delete products',
            'view reports',
            'manage users',
            'manage customers',
            'manage settings'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $role = Role::create(['name' => 'cashier'])
            ->givePermissionTo([
                'view sales',
                'create sales',
                'view products'
            ]);

        $role = Role::create(['name' => 'manager'])
            ->givePermissionTo([
                'view sales',
                'create sales',
                'view products',
                'create products',
                'edit products',
                'view reports',
                'manage customers'
            ]);

        $role = Role::create(['name' => 'admin'])
            ->givePermissionTo(Permission::all());
    }
}
