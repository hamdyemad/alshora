# Architecture Pattern - Service, Repository, Action, Interface

## Overview
The RoleController has been refactored to follow a clean architecture pattern with separation of concerns.

## Architecture Layers

### 1. **Interface Layer** (`app/Interfaces/`)
Defines contracts for repository operations.

**File**: `RoleRepositoryInterface.php`
- Defines all database operation methods
- Ensures consistency across implementations
- Makes code testable and maintainable

**Methods**:
- `getAllWithPermissions()` - Get all roles with permissions
- `findById()` - Find role by ID
- `create()` - Create new role
- `update()` - Update existing role
- `delete()` - Delete role
- `syncPermissions()` - Sync role permissions
- `setTranslation()` - Set translation for role

---

### 2. **Repository Layer** (`app/Repositories/`)
Handles all database operations and queries.

**File**: `RoleRepository.php`
- Implements `RoleRepositoryInterface`
- Direct interaction with Eloquent models
- Encapsulates database logic
- Single source of truth for data access

**Benefits**:
- ✅ Centralized data access
- ✅ Easy to mock for testing
- ✅ Database logic separated from business logic
- ✅ Can switch database implementation easily

---

### 3. **Action Layer** (`app/Actions/Role/`)
Contains single-purpose action classes for specific operations.

**Files**:
- `CreateRoleAction.php` - Handles role creation logic
- `UpdateRoleAction.php` - Handles role update logic
- `DeleteRoleAction.php` - Handles role deletion logic

**Responsibilities**:
- Execute specific business operations
- Coordinate between repository and other services
- Handle complex business logic
- Maintain single responsibility principle

**Example - CreateRoleAction**:
```php
public function execute(array $data): Role
{
    // 1. Get languages
    // 2. Create role with default name
    // 3. Add translations for all languages
    // 4. Sync permissions
    // 5. Return created role
}
```

---

### 4. **Service Layer** (`app/Services/`)
Orchestrates actions and provides high-level business operations.

**File**: `RoleService.php`

**Responsibilities**:
- Coordinate multiple actions
- Provide business logic methods
- Act as facade for controller
- Handle complex workflows

**Methods**:
- `getAllRoles()` - Get all roles
- `getRoleById()` - Get single role
- `getLanguages()` - Get available languages
- `getGroupedPermissions()` - Get permissions grouped by category
- `getValidationRules()` - Generate validation rules
- `createRole()` - Create new role (uses CreateRoleAction)
- `updateRole()` - Update role (uses UpdateRoleAction)
- `deleteRole()` - Delete role (uses DeleteRoleAction)

---

### 5. **Controller Layer** (`app/Http/Controllers/AdminManagement/`)
Thin layer that handles HTTP requests/responses.

**File**: `RoleController.php`

**Responsibilities**:
- Receive HTTP requests
- Validate input
- Call service methods
- Return views/redirects
- Handle HTTP responses

**Example**:
```php
public function store(Request $request)
{
    $request->validate($this->roleService->getValidationRules());
    $this->roleService->createRole($request->all());
    return redirect()->route('admin.admin-management.roles.index')
                    ->with('success', __('Role created successfully'));
}
```

---

## Dependency Injection

### Service Provider
**File**: `app/Providers/RepositoryServiceProvider.php`

Binds interface to implementation:
```php
$this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
```

Registered in `config/app.php`:
```php
App\Providers\RepositoryServiceProvider::class,
```

---

## Data Flow

```
HTTP Request
    ↓
Controller (RoleController)
    ↓
Service (RoleService)
    ↓
Action (CreateRoleAction / UpdateRoleAction / DeleteRoleAction)
    ↓
Repository (RoleRepository)
    ↓
Model (Role)
    ↓
Database
```

---

## Benefits of This Architecture

### 1. **Separation of Concerns**
- Each layer has a specific responsibility
- Easy to understand and maintain
- Changes in one layer don't affect others

### 2. **Testability**
- Easy to mock dependencies
- Can test each layer independently
- Interface-based design enables test doubles

### 3. **Maintainability**
- Clear structure and organization
- Easy to locate and fix bugs
- Simple to add new features

### 4. **Scalability**
- Easy to add new actions
- Can extend functionality without modifying existing code
- Follows SOLID principles

### 5. **Reusability**
- Actions can be reused in different contexts
- Repository methods can be called from anywhere
- Service methods provide consistent interface

### 6. **Flexibility**
- Easy to swap implementations
- Can change database without affecting business logic
- Interface-based design allows multiple implementations

---

## File Structure

```
app/
├── Actions/
│   └── Role/
│       ├── CreateRoleAction.php
│       ├── UpdateRoleAction.php
│       └── DeleteRoleAction.php
├── Http/
│   └── Controllers/
│       └── AdminManagement/
│           └── RoleController.php
├── Interfaces/
│   └── RoleRepositoryInterface.php
├── Providers/
│   └── RepositoryServiceProvider.php
├── Repositories/
│   └── RoleRepository.php
└── Services/
    └── RoleService.php
```

---

## Usage Example

### Creating a Role
```php
// Controller receives request
public function store(Request $request)
{
    // Validate using service
    $request->validate($this->roleService->getValidationRules());
    
    // Create using service (which uses action → repository)
    $this->roleService->createRole($request->all());
    
    // Return response
    return redirect()->route('admin.admin-management.roles.index')
                    ->with('success', __('Role created successfully'));
}
```

### Behind the Scenes
1. **Controller** calls `RoleService::createRole()`
2. **Service** calls `CreateRoleAction::execute()`
3. **Action** calls `RoleRepository::create()`
4. **Repository** interacts with `Role` model
5. **Model** saves to database

---

## Testing Example

```php
// Mock repository in tests
$mockRepository = Mockery::mock(RoleRepositoryInterface::class);
$mockRepository->shouldReceive('create')->once()->andReturn($role);

// Inject mock into action
$action = new CreateRoleAction($mockRepository);
$result = $action->execute($data);

// Assert result
$this->assertInstanceOf(Role::class, $result);
```

---

## Best Practices

1. ✅ Keep controllers thin
2. ✅ Put business logic in services/actions
3. ✅ Keep actions focused (single responsibility)
4. ✅ Use repositories for all database operations
5. ✅ Always code to interfaces
6. ✅ Use dependency injection
7. ✅ Write tests for each layer

---

## Extending the Pattern

To add a new feature:

1. **Add method to Interface** (if needed)
2. **Implement in Repository**
3. **Create Action class** (if complex logic)
4. **Add method to Service**
5. **Use in Controller**

Example: Adding role duplication
```php
// 1. Interface
public function duplicate(Role $role): Role;

// 2. Repository
public function duplicate(Role $role): Role { ... }

// 3. Action
class DuplicateRoleAction { ... }

// 4. Service
public function duplicateRole(Role $role): Role { ... }

// 5. Controller
public function duplicate(Role $role) { ... }
```

---

## Summary

This architecture provides:
- 🎯 **Clear separation** of concerns
- 🧪 **Easy testing** with mocks
- 📦 **Modular design** for maintainability
- 🔄 **Reusable components** across application
- 🚀 **Scalable structure** for growth
- 💡 **SOLID principles** implementation
