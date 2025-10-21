# Permission System Setup Guide

## Overview
Complete permission-based authorization system for menu items and routes.

## Files Modified/Created

### 1. **AuthServiceProvider** (`app/Providers/AuthServiceProvider.php`)
```php
Gate::before(function ($user, $ability) {
    // Check if user has permission through their roles
    if (method_exists($user, 'roles') && $user->roles()->exists()) {
        foreach ($user->roles as $role) {
            if ($role->permessions()->where('key', $ability)->exists()) {
                return true;
            }
        }
    }
    return false;
});
```

### 2. **User Model** (`app/Models/User.php`)
Added:
- `protected $with = ['roles']` - Eager load roles
- `roles()` relationship
- `hasPermission()` method
- `hasAnyPermission()` method

### 3. **Migration** (`database/migrations/2025_10_21_135418_create_user_role_table.php`)
Creates pivot table:
- `user_id` - Foreign key to users
- `role_id` - Foreign key to roles
- Unique constraint on user_id + role_id

### 4. **Menu** (`resources/views/partials/_menu.blade.php`)
Added `@can` directives to all menu items

## How to Use

### 1. Run Migration
```bash
php artisan migrate
```

### 2. Assign Role to User
```php
$user = User::find(1);
$role = Role::find(1); // e.g., Super Admin role
$user->roles()->attach($role->id);
```

### 3. Menu Will Automatically Show/Hide
Based on user's permissions through their roles.

## Permission Keys Used in Menu

### Dashboard
- `dashboard.view`

### Categories
- `categories.departments.view`
- `categories.main.view`
- `categories.sub.view`

### Admin Management
- `admin.roles.view`
- `admin.admins.view`

### Products
- `products.view`
- `products.create`
- `products.edit`
- `products.delete`

## Testing Permissions

### Check if user has permission:
```php
// Using Gate
if (Gate::allows('dashboard.view')) {
    // User has permission
}

// Using @can in Blade
@can('dashboard.view')
    <!-- Show content -->
@endcan

// Using User model method
if (auth()->user()->hasPermission('dashboard.view')) {
    // User has permission
}
```

### Check multiple permissions:
```blade
@canany(['admin.roles.view', 'admin.admins.view'])
    <!-- Show if user has ANY of these permissions -->
@endcanany
```

## Database Structure

```
users
  ↓ (many-to-many)
user_role (pivot)
  ↓
roles
  ↓ (many-to-many)
role_permession (pivot)
  ↓
permessions
```

## How It Works

1. **User logs in** → Laravel loads user with roles
2. **Menu renders** → Blade checks `@can('permission.key')`
3. **Gate::before fires** → Checks if user's roles have the permission
4. **Permission found** → Menu item shows
5. **Permission not found** → Menu item hidden

## Adding New Permissions

### 1. Add to PermessionSeeder
```php
['key' => 'new.permission', 'translations' => [
    'name' => ['en' => 'New Permission', 'ar' => 'صلاحية جديدة'],
    'group_by' => ['en' => 'Group Name', 'ar' => 'اسم المجموعة'],
]],
```

### 2. Run Seeder
```bash
php artisan db:seed --class=PermessionSeeder
```

### 3. Use in Menu
```blade
@can('new.permission')
    <li><a href="#">New Menu Item</a></li>
@endcan
```

## Protecting Routes

Add middleware to routes:
```php
Route::get('/admin/roles', [RoleController::class, 'index'])
    ->middleware('can:admin.roles.view');
```

Or in controller constructor:
```php
public function __construct()
{
    $this->middleware('can:admin.roles.view')->only(['index', 'show']);
    $this->middleware('can:admin.roles.create')->only(['create', 'store']);
    $this->middleware('can:admin.roles.edit')->only(['edit', 'update']);
    $this->middleware('can:admin.roles.delete')->only(['destroy']);
}
```

## Troubleshooting

### Menu items not showing?
1. Check if user has roles: `auth()->user()->roles`
2. Check if role has permissions: `$role->permessions`
3. Check permission key matches exactly
4. Clear cache: `php artisan cache:clear`

### Permission check always fails?
1. Ensure `user_role` pivot table exists
2. Ensure user is assigned to a role
3. Ensure role has the permission
4. Check `Gate::before` is registered in AuthServiceProvider

## Super Admin Bypass (Optional)

Uncomment in AuthServiceProvider:
```php
Gate::before(function ($user, $ability) {
    if ($user->hasRole('super_admin')) {
        return true; // Bypass all permission checks
    }
    // ... rest of code
});
```

## Security Best Practices

1. ✅ Always use `@can` in views
2. ✅ Always use middleware on routes
3. ✅ Never trust client-side checks only
4. ✅ Assign permissions through roles, not directly
5. ✅ Use descriptive permission keys (e.g., `admin.roles.view`)
6. ✅ Group permissions logically
7. ✅ Test permission checks thoroughly

## Complete Example

```php
// 1. Create role
$role = Role::create(['name' => 'Content Manager']);

// 2. Assign permissions
$permissions = Permession::whereIn('key', [
    'dashboard.view',
    'products.view',
    'products.create',
    'products.edit'
])->get();
$role->permessions()->sync($permissions->pluck('id'));

// 3. Assign role to user
$user = User::find(1);
$user->roles()->attach($role->id);

// 4. User now sees only allowed menu items!
```

## Summary

✅ Permission system fully integrated
✅ Menu items show/hide based on permissions
✅ User → Roles → Permissions relationship
✅ Dynamic Gate checking
✅ Eager loading for performance
✅ Ready for production use
