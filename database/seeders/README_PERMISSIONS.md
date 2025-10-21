# Permission Seeder with Translations

## Overview
The `PermessionSeeder` now supports automatic translation storage for permission names in both **English** and **Arabic**.

## Database Structure
- **Languages Table**: Stores available languages (English, Arabic)
- **Permessions Table**: Stores permission keys and groups
- **Translations Table**: Polymorphic table storing translations for any model

## How It Works

### 1. Language Seeder
First, the `LanguageSeeder` creates two languages:
- **English** (code: `en`)
- **Arabic** (code: `ar`)

### 2. Permission Seeder
The `PermessionSeeder`:
1. Retrieves available languages from the database
2. Creates each permission record
3. Automatically adds translations for each permission using the `getPermissionTranslations()` helper method
4. Stores translations in the `translations` table via the `Translation` trait

### 3. Translation Trait
The `Translation` trait provides:
- `setTranslation($key, $value, $locale)` - Store a translation
- `getTranslation($key, $locale)` - Retrieve a translation

## Running the Seeders

### Run all seeders:
```bash
php artisan db:seed
```

### Run specific seeders:
```bash
# Seed languages first
php artisan db:seed --class=LanguageSeeder

# Then seed permissions with translations
php artisan db:seed --class=PermessionSeeder
```

### Fresh migration with seeding:
```bash
php artisan migrate:fresh --seed
```

## Usage Example

### Retrieve a permission with translation:
```php
$permission = Permession::where('key', 'dashboard.view')->first();

// Get English translation
$englishName = $permission->getTranslation('name', 'en');
// Returns: "View Dashboard"

// Get Arabic translation
$arabicName = $permission->getTranslation('name', 'ar');
// Returns: "عرض لوحة التحكم"
```

### Add a new permission with translations:
```php
$permission = Permession::create([
    'key' => 'new.permission',
    'group_by' => 'New Group'
]);

$permission->setTranslation('name', 'New Permission', 'en');
$permission->setTranslation('name', 'صلاحية جديدة', 'ar');
```

## Translation Coverage
All **200+ permissions** have been translated into both English and Arabic, including:
- Dashboard permissions
- Category management (Departments, Main, Sub)
- Products & Product Setup
- User Management (Admins, Vendors, Users)
- Orders & Order Settings
- Accounting & Withdraw
- Blog Management
- Reports
- Area Settings
- System Settings

## Notes
- The seeder uses `updateOrCreate` for languages to avoid duplicates
- Translations are stored with the key `'name'` in the translations table
- The `Translation` trait uses polymorphic relationships for flexibility
- Languages must exist before running the permission seeder
