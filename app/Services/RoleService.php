<?php

namespace App\Services;

use App\Interfaces\RoleRepositoryInterface;
use App\Models\Role;
use App\Models\Language;
use App\Models\Permession;
use Illuminate\Database\Eloquent\Collection;

class RoleService
{
    public function __construct(public RoleRepositoryInterface $roleRepository)
    {
    }

    /**
     * Get all roles with permissions
     */
    public function getAllRoles(): Collection
    {
        return $this->roleRepository->getAllWithPermissions();
    }

    /**
     * Get a single role by ID
     */
    public function getRoleById(int $id): ?Role
    {
        return $this->roleRepository->findById($id);
    }

    /**
     * Get all languages
     */
    public function getLanguages(): Collection
    {
        return Language::all();
    }

    /**
     * Get grouped permissions
     */
    public function getGroupedPermissions(): Collection
    {
        $permissions = Permession::all();
        
        return $permissions->groupBy(function($permission) {
            return $permission->getTranslation('group_by', app()->getLocale()) ?? 'Other';
        });
    }

    /**
     * Create a new role
     */
    public function createRole(array $data): Role
    {
        // Get all languages
        $languages = Language::all();
        
        // Create the role without name (name is stored only in translations)
        $role = $this->roleRepository->create([]);

        // Add translations for all languages
        foreach ($languages as $language) {
            if (isset($data['name_' . $language->code]) && !empty($data['name_' . $language->code])) {
                $role->setTranslation(
                    'name',
                    $data['name_' . $language->code],
                    $language->code
                );
            }
        }

        // Sync permissions if provided
        if (isset($data['permissions']) && is_array($data['permissions'])) {
            $this->roleRepository->syncPermissions($role, $data['permissions']);
        }

        // Refresh the role to get updated data
        return $role->fresh('permessions');
    }

    /**
     * Update an existing role
     */
    public function updateRole(Role $role, array $data): Role
    {
        // Get all languages
        $languages = Language::all();
        
        // Update translations for all languages (no name column to update)
        foreach ($languages as $language) {
            if (isset($data['name_' . $language->code]) && !empty($data['name_' . $language->code])) {
                $role->setTranslation(
                    'name',
                    $data['name_' . $language->code],
                    $language->code
                );
            }
        }

        // Sync permissions if provided
        if (isset($data['permissions']) && is_array($data['permissions'])) {
            $this->roleRepository->syncPermissions($role, $data['permissions']);
        } else {
            // Clear all permissions if none provided
            $this->roleRepository->syncPermissions($role, []);
        }

        // Refresh the role to get updated data
        return $role->fresh('permessions');
    }

    /**
     * Delete a role
     */
    public function deleteRole(Role $role): bool
    {
        // Detach all permissions before deleting
        $role->permessions()->detach();
        
        // Delete all translations
        $role->translations()->delete();
        
        // Delete the role
        return $this->roleRepository->delete($role);
    }

    /**
     * Assign permissions to a role
     */
    public function assignPermissions(Role $role, array $permissionIds): void
    {
        $this->roleRepository->syncPermissions($role, $permissionIds);
    }

    /**
     * Add permissions to a role
     */
    public function addPermissions(Role $role, array $permissionIds): void
    {
        $currentPermissions = $role->permessions->pluck('id')->toArray();
        $allPermissions = array_unique(array_merge($currentPermissions, $permissionIds));
        
        $this->roleRepository->syncPermissions($role, $allPermissions);
    }

    /**
     * Remove permissions from a role
     */
    public function removePermissions(Role $role, array $permissionIds): void
    {
        $currentPermissions = $role->permessions->pluck('id')->toArray();
        $remainingPermissions = array_diff($currentPermissions, $permissionIds);
        
        $this->roleRepository->syncPermissions($role, $remainingPermissions);
    }
}
