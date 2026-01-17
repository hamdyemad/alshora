# Lawyer Accounting API Documentation

## Overview
هذه الـ APIs تسمح للمحامي بإدارة معاملاته المالية من التطبيق، بما في ذلك عرض الإحصائيات، إضافة معاملات جديدة، تعديل وحذف المعاملات.

## Authentication
جميع الـ endpoints تتطلب authentication باستخدام Sanctum token في الـ header:
```
Authorization: Bearer {token}
```

## Base URL
```
/api/v1/lawyer/accounting
```

---

## Endpoints

### 1. Get Financial Statistics
احصل على الإحصائيات المالية للمحامي (الدخل، المصروفات، الربح)

**Endpoint:** `GET /api/v1/lawyer/accounting/stats`

**Query Parameters:**
- `date_from` (optional): تاريخ البداية (default: بداية الشهر الحالي) - Format: Y-m-d
- `date_to` (optional): تاريخ النهاية (default: نهاية الشهر الحالي) - Format: Y-m-d

**Response:**
```json
{
    "success": true,
    "data": {
        "income": 15000.00,
        "expenses": 5000.00,
        "profit": 10000.00,
        "date_from": "2026-01-01",
        "date_to": "2026-01-31"
    }
}
```

---

### 2. Get Transactions List
احصل على قائمة المعاملات المالية مع pagination

**Endpoint:** `GET /api/v1/lawyer/accounting/transactions`

**Query Parameters:**
- `date_from` (optional): تاريخ البداية - Format: Y-m-d
- `date_to` (optional): تاريخ النهاية - Format: Y-m-d
- `type` (optional): نوع المعاملة (income, expense)
- `category` (optional): الفئة (consultation, subscription, office_rent, utilities, marketing, salary, other)
- `per_page` (optional): عدد العناصر في الصفحة (default: 15)

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "lawyer_id": 5,
            "appointment_id": null,
            "type": "income",
            "amount": 500.00,
            "category": "consultation",
            "description": "استشارة قانونية",
            "transaction_date": "2026-01-15",
            "created_at": "2026-01-15 10:30:00",
            "updated_at": "2026-01-15 10:30:00",
            "appointment": null,
            "can_edit": true,
            "can_delete": true
        },
        {
            "id": 2,
            "lawyer_id": 5,
            "appointment_id": 123,
            "type": "income",
            "amount": 1000.00,
            "category": "consultation",
            "description": "حجز موعد",
            "transaction_date": "2026-01-16",
            "created_at": "2026-01-16 14:20:00",
            "updated_at": "2026-01-16 14:20:00",
            "appointment": {
                "id": 123,
                "appointment_date": "2026-01-16",
                "status": "completed"
            },
            "can_edit": false,
            "can_delete": false
        }
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 3,
        "per_page": 15,
        "total": 45
    }
}
```

---

### 3. Get Single Transaction
احصل على تفاصيل معاملة واحدة

**Endpoint:** `GET /api/v1/lawyer/accounting/transactions/{id}`

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "lawyer_id": 5,
        "appointment_id": null,
        "type": "expense",
        "amount": 200.00,
        "category": "office_rent",
        "description": "إيجار المكتب لشهر يناير",
        "transaction_date": "2026-01-01",
        "created_at": "2026-01-01 09:00:00",
        "updated_at": "2026-01-01 09:00:00",
        "appointment": null,
        "can_edit": true,
        "can_delete": true
    }
}
```

---

### 4. Create Transaction
إضافة معاملة مالية جديدة

**Endpoint:** `POST /api/v1/lawyer/accounting/transactions`

**Request Body:**
```json
{
    "type": "expense",
    "amount": 300.50,
    "category": "marketing",
    "description": "إعلانات على فيسبوك",
    "transaction_date": "2026-01-17"
}
```

**Validation Rules:**
- `type`: required, must be 'income' or 'expense'
- `amount`: required, numeric, minimum 0
- `category`: optional, string, max 255 characters
- `description`: optional, string
- `transaction_date`: required, valid date

**Response:**
```json
{
    "success": true,
    "message": "تم إضافة المعاملة بنجاح",
    "data": {
        "id": 50,
        "lawyer_id": 5,
        "appointment_id": null,
        "type": "expense",
        "amount": 300.50,
        "category": "marketing",
        "description": "إعلانات على فيسبوك",
        "transaction_date": "2026-01-17",
        "created_at": "2026-01-17 15:30:00",
        "updated_at": "2026-01-17 15:30:00",
        "appointment": null,
        "can_edit": true,
        "can_delete": true
    }
}
```

---

### 5. Update Transaction
تعديل معاملة مالية (لا يمكن تعديل المعاملات المرتبطة بالحجوزات)

**Endpoint:** `PUT /api/v1/lawyer/accounting/transactions/{id}`

**Request Body:**
```json
{
    "amount": 350.00,
    "description": "إعلانات على فيسبوك وإنستجرام"
}
```

**Validation Rules:**
- `type`: optional, must be 'income' or 'expense'
- `amount`: optional, numeric, minimum 0
- `category`: optional, string, max 255 characters
- `description`: optional, string
- `transaction_date`: optional, valid date

**Response:**
```json
{
    "success": true,
    "message": "تم تحديث المعاملة بنجاح",
    "data": {
        "id": 50,
        "lawyer_id": 5,
        "appointment_id": null,
        "type": "expense",
        "amount": 350.00,
        "category": "marketing",
        "description": "إعلانات على فيسبوك وإنستجرام",
        "transaction_date": "2026-01-17",
        "created_at": "2026-01-17 15:30:00",
        "updated_at": "2026-01-17 16:00:00",
        "appointment": null,
        "can_edit": true,
        "can_delete": true
    }
}
```

**Error Response (Cannot Update Appointment Transaction):**
```json
{
    "success": false,
    "message": "لا يمكن تعديل معاملة مرتبطة بحجز"
}
```

---

### 6. Delete Transaction
حذف معاملة مالية (لا يمكن حذف المعاملات المرتبطة بالحجوزات)

**Endpoint:** `DELETE /api/v1/lawyer/accounting/transactions/{id}`

**Response:**
```json
{
    "success": true,
    "message": "تم حذف المعاملة بنجاح"
}
```

**Error Response (Cannot Delete Appointment Transaction):**
```json
{
    "success": false,
    "message": "لا يمكن حذف معاملة مرتبطة بحجز"
}
```

---

### 7. Get Transactions by Category
احصل على المعاملات مجمعة حسب الفئة

**Endpoint:** `GET /api/v1/lawyer/accounting/by-category`

**Query Parameters:**
- `date_from` (optional): تاريخ البداية - Format: Y-m-d
- `date_to` (optional): تاريخ النهاية - Format: Y-m-d

**Response:**
```json
{
    "success": true,
    "data": {
        "consultation": {
            "total": 15000.00,
            "count": 30
        },
        "subscription": {
            "total": 5000.00,
            "count": 5
        },
        "office_rent": {
            "total": 2000.00,
            "count": 1
        },
        "marketing": {
            "total": 1500.00,
            "count": 3
        }
    }
}
```

---

### 8. Get Chart Data
احصل على بيانات الرسم البياني (الدخل مقابل المصروفات)

**Endpoint:** `GET /api/v1/lawyer/accounting/chart-data`

**Query Parameters:**
- `date_from` (optional): تاريخ البداية - Format: Y-m-d
- `date_to` (optional): تاريخ النهاية - Format: Y-m-d

**Response:**
```json
{
    "success": true,
    "data": {
        "income": 15000.00,
        "expenses": 5000.00,
        "profit": 10000.00
    }
}
```

---

## Error Responses

### 404 - Lawyer Not Found
```json
{
    "success": false,
    "message": "المحامي غير موجود"
}
```

### 404 - Transaction Not Found
```json
{
    "success": false,
    "message": "المعاملة غير موجودة"
}
```

### 422 - Validation Error
```json
{
    "success": false,
    "message": "خطأ في البيانات المدخلة",
    "errors": {
        "amount": [
            "The amount field is required."
        ],
        "type": [
            "The selected type is invalid."
        ]
    }
}
```

### 401 - Unauthorized
```json
{
    "message": "Unauthenticated."
}
```

---

## Categories Available
الفئات المتاحة للمعاملات:
- `consultation` - استشارة
- `subscription` - اشتراك
- `office_rent` - إيجار مكتب
- `utilities` - مرافق
- `marketing` - تسويق
- `salary` - راتب
- `other` - أخرى

---

## Notes
- المعاملات المرتبطة بالحجوزات (appointment_id != null) يتم إنشاؤها تلقائياً ولا يمكن تعديلها أو حذفها
- جميع المبالغ يتم إرجاعها كـ float
- التواريخ بصيغة Y-m-d (مثال: 2026-01-17)
- الأوقات بصيغة Y-m-d H:i:s (مثال: 2026-01-17 15:30:00)
