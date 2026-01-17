# Troubleshooting: Lawyer Accounting API

## Error: "Lawyer not found"

### Problem
عند استدعاء `/api/v1/lawyer/accounting/stats` أو أي endpoint آخر في الـ accounting، تحصل على:
```json
{
    "success": false,
    "message": "Lawyer not found",
    "data": [],
    "errors": []
}
```

### Root Cause
المستخدم المسجل دخوله ليس لديه lawyer profile مرتبط به.

### Solution

#### 1. تحقق من نوع المستخدم
تأكد أن المستخدم الذي تستخدمه للاختبار هو محامي:

```sql
-- Check user type
SELECT u.id, u.email, u.user_type_id, ut.name as user_type
FROM users u
LEFT JOIN user_types ut ON u.user_type_id = ut.id
WHERE u.email = 'your_test_email@example.com';
```

يجب أن يكون `user_type_id = 3` (LAWYER_TYPE)

#### 2. تحقق من وجود lawyer profile
```sql
-- Check if lawyer profile exists
SELECT l.id, l.user_id, u.email
FROM lawyers l
INNER JOIN users u ON l.user_id = u.id
WHERE u.email = 'your_test_email@example.com';
```

إذا لم يكن هناك نتائج، المستخدم ليس لديه lawyer profile.

#### 3. إنشاء محامي للاختبار

**Option A: من Admin Panel**
1. اذهب إلى `/admin/lawyers/create`
2. أنشئ محامي جديد
3. استخدم البريد الإلكتروني وكلمة المرور للدخول

**Option B: من Database**
```sql
-- Create a test lawyer user
INSERT INTO users (uuid, email, password, user_type_id, created_at, updated_at)
VALUES (UUID(), 'lawyer@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, NOW(), NOW());
-- Password is: password

-- Get the user_id
SET @user_id = LAST_INSERT_ID();

-- Create lawyer profile
INSERT INTO lawyers (user_id, active, created_at, updated_at)
VALUES (@user_id, 1, NOW(), NOW());
```

#### 4. الحصول على Token
```bash
POST /api/v1/auth/login
Content-Type: application/json

{
    "email": "lawyer@test.com",
    "password": "password"
}
```

#### 5. استخدام Token في Accounting APIs
```bash
GET /api/v1/lawyer/accounting/stats
Authorization: Bearer YOUR_TOKEN_HERE
```

---

## Debug Response

عند حدوث الخطأ، الـ API الآن يرجع معلومات debug:

```json
{
    "success": false,
    "message": "Lawyer not found",
    "debug": {
        "user_id": 123,
        "user_type_id": 4,
        "has_lawyer_profile": false
    }
}
```

### تفسير Debug Info:
- `user_id`: معرف المستخدم المسجل دخول
- `user_type_id`: نوع المستخدم
  - `1` = Super Admin
  - `2` = Admin
  - `3` = Lawyer ✅
  - `4` = Customer
- `has_lawyer_profile`: هل يوجد lawyer profile

---

## User Types Reference

| ID | Type | Can Access Accounting API |
|----|------|---------------------------|
| 1  | Super Admin | ❌ No |
| 2  | Admin | ❌ No |
| 3  | Lawyer | ✅ Yes |
| 4  | Customer | ❌ No |

---

## Testing Checklist

✅ المستخدم مسجل دخول (Bearer Token صحيح)
✅ المستخدم من نوع Lawyer (`user_type_id = 3`)
✅ يوجد lawyer profile في جدول `lawyers` مرتبط بالمستخدم
✅ الـ lawyer profile نشط (`active = 1`)

---

## Common Mistakes

### ❌ استخدام customer token
```json
// Wrong - Customer trying to access lawyer endpoints
{
    "user_type_id": 4,  // Customer
    "has_lawyer_profile": false
}
```

### ❌ استخدام admin token
```json
// Wrong - Admin trying to access lawyer endpoints
{
    "user_type_id": 2,  // Admin
    "has_lawyer_profile": false
}
```

### ✅ استخدام lawyer token
```json
// Correct - Lawyer accessing their accounting
{
    "user_type_id": 3,  // Lawyer
    "has_lawyer_profile": true
}
```

---

## Quick Fix Script

إذا كنت تريد تحويل مستخدم موجود إلى محامي:

```sql
-- Update user type to lawyer
UPDATE users SET user_type_id = 3 WHERE email = 'your_email@example.com';

-- Create lawyer profile if not exists
INSERT INTO lawyers (user_id, active, created_at, updated_at)
SELECT id, 1, NOW(), NOW()
FROM users
WHERE email = 'your_email@example.com'
AND NOT EXISTS (
    SELECT 1 FROM lawyers WHERE user_id = users.id
);
```

---

## API Endpoints Requiring Lawyer Profile

جميع هذه الـ endpoints تتطلب أن يكون المستخدم محامي:

- `GET /api/v1/lawyer/accounting/stats`
- `GET /api/v1/lawyer/accounting/transactions`
- `POST /api/v1/lawyer/accounting/transactions`
- `GET /api/v1/lawyer/accounting/transactions/{id}`
- `PUT /api/v1/lawyer/accounting/transactions/{id}`
- `DELETE /api/v1/lawyer/accounting/transactions/{id}`
- `GET /api/v1/lawyer/accounting/by-category`
- `GET /api/v1/lawyer/accounting/chart-data`

---

## Contact

إذا استمرت المشكلة بعد التحقق من كل النقاط أعلاه، تحقق من:
1. الـ middleware في `routes/api/v1/lawyer.php`
2. العلاقة `lawyer()` في `app/Models/User.php`
3. الـ logs في `storage/logs/laravel.log`
