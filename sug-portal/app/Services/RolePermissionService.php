<?php

namespace App\Services;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionService
{
    /**
     * Assign a role to a user.
     */
    public function assignRoleToUser(User $user, string $roleName): void
    {
        $user->assignRole($roleName);
    }

    /**
     * Remove a role from a user.
     */
    public function removeRoleFromUser(User $user, string $roleName): void
    {
        $user->removeRole($roleName);
    }

    /**
     * Sync roles for a user.
     */
    public function syncRolesForUser(User $user, array $roles): void
    {
        $user->syncRoles($roles);
    }

    /**
     * Create a new role.
     */
    public function createRole(string $name): Role
    {
        return Role::create(['name' => $name]);
    }

    /**
     * Create a new permission.
     */
    public function createPermission(string $name): Permission
    {
        return Permission::create(['name' => $name]);
    }

    /**
     * Assign permission to a role.
     */
    public function givePermissionToRole(Role $role, string $permissionName): void
    {
        $role->givePermissionTo($permissionName);
    }

    /**
     * Revoke permission from a role.
     */
    public function revokePermissionFromRole(Role $role, string $permissionName): void
    {
        $role->revokePermission($permissionName);
    }
}
