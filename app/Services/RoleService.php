<?php

namespace App\Services;

use App\Models\Role;
use App\Models\Permession;
use Illuminate\Support\Facades\DB;

class RoleService
{
    /**
     * Get all roles with filters and pagination
     */
    public function getAll($filters = [], $perPage = 10)
    {
        $query = Role::with('permessions');

        // Apply search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('translations', function($q) use ($search) {
                $q->where('lang_value', 'like', "%{$search}%");
            });
        }

        // Apply date filters
        if (!empty($filters['created_date_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_date_from']);
        }

        if (!empty($filters['created_date_to'])) {
            $query->whereDate('created_at', '<=', $filters['created_date_to']);
        }

        return $perPage > 0 ? $query->latest()->paginate($perPage) : $query->latest()->get();
    }

    /**
     * Get role by ID
     */
    public function getRoleById($id)
    {
        return Role::with('permessions')->findOrFail($id);
    }

    /**
     * Create new role
     */
    public function createRole($data)
    {
        DB::beginTransaction();
        try {
            $role = Role::create([]);

            // Save translations - handle both formats: name_en/name_ar and name['en']/name['ar']
            $languages = \App\Models\Language::all();
            foreach ($languages as $language) {
                $key = 'name_' . $language->code;
                if (isset($data[$key]) && !empty($data[$key])) {
                    $role->setTranslation('name', $language->code, $data[$key]);
                }
            }

            // Attach permissions
            if (!empty($data['permissions'])) {
                $role->permessions()->attach($data['permissions']);
            }

            DB::commit();
            return $role;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update role
     */
    public function updateRole($role, $data)
    {
        DB::beginTransaction();
        try {
            // Update translations - handle both formats: name_en/name_ar and name['en']/name['ar']
            $languages = \App\Models\Language::all();
            foreach ($languages as $language) {
                $key = 'name_' . $language->code;
                if (isset($data[$key]) && !empty($data[$key])) {
                    $role->setTranslation('name', $language->code, $data[$key]);
                }
            }

            // Sync permissions
            if (isset($data['permissions'])) {
                $role->permessions()->sync($data['permissions']);
            } else {
                // If no permissions provided, remove all
                $role->permessions()->sync([]);
            }

            DB::commit();
            return $role;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete role
     */
    public function deleteRole($role)
    {
        DB::beginTransaction();
        try {
            $role->permessions()->detach();
            $role->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get all permissions
     */
    public function getAllPermissions()
    {
        return Permession::all();
    }

    /**
     * Get all roles (alias for getAll without pagination)
     */
    public function getAllRoles($filters = [])
    {
        return $this->getAll($filters, 0);
    }

    /**
     * Get languages
     */
    public function getLanguages()
    {
        return \App\Models\Language::all();
    }

    /**
     * Get grouped permissions
     */
    public function getGroupedPermissions()
    {
        $permissions = Permession::all();
        
        // Define the order of actions
        $actionOrder = ['view', 'create', 'edit', 'delete', 'manage', 'approve', 'reject', 'settings', 'send'];
        
        // Group permissions by group_by translation
        $grouped = collect();
        $locale = app()->getLocale();
        
        foreach ($permissions as $permission) {
            $key = $permission->key;
            
            // Get group name from group_by translation, fallback to key parsing
            $groupName = $permission->getTranslation('group_by', $locale);
            
            if (empty($groupName)) {
                // Fallback: parse from key
                if (strpos($key, '.') !== false) {
                    $parts = explode('.', $key);
                    array_pop($parts);
                    $groupName = ucwords(str_replace(['-', '_'], ' ', end($parts)));
                } else {
                    $groupName = ucwords(str_replace(['-', '_'], ' ', $key));
                }
            }
            
            // Get action from key
            $action = '';
            if (strpos($key, '.') !== false) {
                $parts = explode('.', $key);
                $action = end($parts);
            } else {
                $action = 'other';
            }
            
            if (!$grouped->has($groupName)) {
                $grouped->put($groupName, collect());
            }
            
            $grouped->get($groupName)->push([
                'permission' => $permission,
                'action' => $action
            ]);
        }
        
        // Sort permissions within each group by action order
        $grouped = $grouped->map(function($permissions) use ($actionOrder) {
            return $permissions->sortBy(function($item) use ($actionOrder) {
                $index = array_search($item['action'], $actionOrder);
                return $index !== false ? $index : 999;
            })->values();
        });
        
        // Sort groups alphabetically
        return $grouped->sortKeys();
    }
    
    /**
     * Get action badge color
     */
    public function getActionBadgeColor($action)
    {
        return match($action) {
            'view' => 'info',
            'create' => 'success',
            'edit' => 'warning',
            'delete' => 'danger',
            'manage' => 'primary',
            'approve', 'accept' => 'success',
            'reject' => 'danger',
            'send' => 'primary',
            default => 'secondary'
        };
    }
    
    /**
     * Get resource display name
     */
    public function getResourceDisplayName($resource)
    {
        // Convert to readable format
        // e.g., 'roles' -> 'Roles'
        // e.g., 'sections-of-laws' -> 'Sections Of Laws'
        // e.g., 'products' -> 'Products'
        return ucwords(str_replace(['-', '_'], ' ', $resource));
    }
}
