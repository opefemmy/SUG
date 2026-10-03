<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define Permissions
        $permissions = [
            // User Management
            'manage users', 'view users', 'edit users', 'delete users',
            // Student Management
            'view students', 'edit students', 'import students', 'promote students',
            // Academic Management
            'manage schools', 'manage departments', 'manage programmes', 'manage sessions', 'manage levels',
            // Fee Management
            'view fees', 'edit fees', 'view payments', 'mark unpaid',
            // CMS/Settings
            'edit settings', 'manage news', 'manage events', 'manage pages',
            // Support/Complaints
            'view complaints', 'verify complaints', 'manage support tickets',
            // System
            'impersonate users', 'view system logs',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Define Preset Roles

        // 1. Super Admin: Has all permissions
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdminRole->syncPermissions(Permission::all());

        // 2. Staff: View-only for students and payments
        $staffRole = Role::firstOrCreate(['name' => 'Staff']);
        $staffRole->syncPermissions([
            'view students',
            'view payments',
            'view users',
            'view complaints'
        ]);

        // 3. Editor: CMS and News management
        $editorRole = Role::firstOrCreate(['name' => 'Editor']);
        $editorRole->syncPermissions([
            'edit settings',
            'manage news',
            'manage events',
            'manage pages'
        ]);

        // Note: We keep the 'admin' role for backward compatibility but we can give it all permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());
    }
}
