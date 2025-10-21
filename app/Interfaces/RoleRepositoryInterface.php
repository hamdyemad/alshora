<?php

namespace App\Interfaces;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

interface RoleRepositoryInterface
{
    /**
     * Get all roles with their permissions
     */
    public function getAllWithPermissions(): Collection;

    /**
     * Find a role by ID
     */
    public function findById(int $id): ?Role;

    /**
     * Create a new role
     */
    public function create(array $data): Role;

    /**
     * Update a role
     */
    public function update(Role $role, array $data): Role;

    /**
     * Delete a role
     */
    public function delete(Role $role): bool;

    /**
     * Sync permissions for a role
     */
    public function syncPermissions(Role $role, array $permissionIds): void;

    /**
     * Set translation for a role
     */
    public function setTranslation(Role $role, string $key, string $value, string $locale): void;
}
