<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminService
{
    /**
     * Get all admins with filters and pagination
     */
    public function getAll($filters = [], $perPage = 10)
    {
        $query = User::with('roles')->has('roles');

        // Apply search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if (isset($filters['is_blocked']) && $filters['is_blocked'] !== '') {
            $query->where('is_blocked', $filters['is_blocked']);
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
     * Get admin by ID
     */
    public function getAdminById($id)
    {
        return User::with('roles')->findOrFail($id);
    }

    /**
     * Create new admin
     */
    public function createAdmin($data)
    {
        DB::beginTransaction();
        try {
            $admin = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'user_type_id' => 2, // Admin type (1=super_admin, 2=admin, 3=lawyer, 4=customer)
                'is_blocked' => $data['is_blocked'] ?? false,
                'uuid' => Str::uuid()->toString(),
            ]);

            // Attach roles
            if (!empty($data['roles'])) {
                $admin->roles()->attach($data['roles']);
            }

            DB::commit();
            return $admin;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update admin
     */
    public function updateAdmin($admin, $data)
    {
        DB::beginTransaction();
        try {
            $updateData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'is_blocked' => $data['is_blocked'] ?? false,
            ];

            // Only update password if provided
            if (!empty($data['password'])) {
                $updateData['password'] = Hash::make($data['password']);
            }

            $admin->update($updateData);

            // Sync roles
            if (isset($data['roles'])) {
                $admin->roles()->sync($data['roles']);
            }

            DB::commit();
            return $admin;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete admin
     */
    public function deleteAdmin($admin)
    {
        DB::beginTransaction();
        try {
            $admin->roles()->detach();
            $admin->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Toggle admin blocked status
     */
    public function toggleBlocked($admin)
    {
        $admin->update(['is_blocked' => !$admin->is_blocked]);
        return $admin;
    }
}
