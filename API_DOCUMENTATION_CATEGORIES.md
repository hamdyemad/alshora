# Transaction Categories API Documentation

## Overview
تم إضافة Enum للـ categories لضمان استخدام قيم صحيحة فقط عند إنشاء أو تعديل المعاملات المالية.

---

## Available Categories

| Value | English | Arabic |
|-------|---------|--------|
| `consultation` | Consultation | استشارة |
| `subscription` | Subscription | اشتراك |
| `office_rent` | Office Rent | إيجار مكتب |
| `utilities` | Utilities | مرافق |
| `marketing` | Marketing | تسويق |
| `salary` | Salary | راتب |
| `other` | Other | أخرى |

---

## Get Categories Endpoint

### Get All Available Categories
احصل على قائمة جميع الفئات المتاحة مع الترجمات

**Endpoint:** `GET /api/v1/lawyer/accounting/categories`

**Authentication:** Required (Bearer Token)

**Response:**
```json
{
    "success": true,
    "data": {
        "consultation": "Consultation",
        "subscription": "Subscription",
        "office_rent": "Office Rent",
        "utilities": "Utilities",
        "marketing": "Marketing",
        "salary": "Salary",
        "other": "Other"
    }
}
```

**Response (Arabic):**
```json
{
    "success": true,
    "data": {
        "consultation": "استشارة",
        "subscription": "اشتراك",
        "office_rent": "إيجار مكتب",
        "utilities": "مرافق",
        "marketing": "تسويق",
        "salary": "راتب",
        "other": "أخرى"
    }
}
```

---

## Usage in Create/Update Transaction

### Create Transaction with Category
```javascript
POST /api/v1/lawyer/accounting/transactions
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json

{
    "type": "expense",
    "amount": 500.00,
    "category": "office_rent",  // ✅ Must be one of the enum values
    "description": "Monthly office rent",
    "transaction_date": "2026-01-17"
}
```

### Validation Rules

**Create Transaction:**
```php
'category' => 'nullable|in:consultation,subscription,office_rent,utilities,marketing,salary,other'
```

**Update Transaction:**
```php
'category' => 'nullable|in:consultation,subscription,office_rent,utilities,marketing,salary,other'
```

---

## Validation Errors

### Invalid Category
```json
{
    "success": false,
    "message": "The given data was invalid.",
    "errors": {
        "category": [
            "The selected category is invalid."
        ]
    }
}
```

**Example of Invalid Request:**
```json
{
    "type": "expense",
    "amount": 500.00,
    "category": "invalid_category",  // ❌ Not in enum
    "transaction_date": "2026-01-17"
}
```

---

## Implementation Details

### Enum Class
```php
// app/Enums/TransactionCategory.php

enum TransactionCategory: string
{
    case CONSULTATION = 'consultation';
    case SUBSCRIPTION = 'subscription';
    case OFFICE_RENT = 'office_rent';
    case UTILITIES = 'utilities';
    case MARKETING = 'marketing';
    case SALARY = 'salary';
    case OTHER = 'other';
}
```

### Model Methods
```php
// Get categories with translations
LawyerTransaction::getCategories();
// Returns: ['consultation' => 'Consultation', ...]

// Get category values only
LawyerTransaction::getCategoryValues();
// Returns: ['consultation', 'subscription', ...]
```

---

## Frontend Integration

### Step 1: Fetch Categories
```javascript
async function fetchCategories() {
    const response = await fetch('/api/v1/lawyer/accounting/categories', {
        headers: {
            'Authorization': 'Bearer YOUR_TOKEN',
            'Accept': 'application/json'
        }
    });
    
    const data = await response.json();
    return data.data;
}
```

### Step 2: Populate Dropdown
```javascript
const categories = await fetchCategories();

const selectElement = document.getElementById('category');
selectElement.innerHTML = '<option value="">Select Category</option>';

Object.entries(categories).forEach(([value, label]) => {
    const option = document.createElement('option');
    option.value = value;
    option.textContent = label;
    selectElement.appendChild(option);
});
```

### Step 3: Create Transaction
```javascript
async function createTransaction(data) {
    const response = await fetch('/api/v1/lawyer/accounting/transactions', {
        method: 'POST',
        headers: {
            'Authorization': 'Bearer YOUR_TOKEN',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            type: data.type,
            amount: data.amount,
            category: data.category,  // Value from dropdown
            description: data.description,
            transaction_date: data.date
        })
    });
    
    return await response.json();
}
```

---

## React Example

```jsx
import { useState, useEffect } from 'react';

function TransactionForm() {
    const [categories, setCategories] = useState({});
    const [formData, setFormData] = useState({
        type: 'expense',
        amount: '',
        category: '',
        description: '',
        transaction_date: new Date().toISOString().split('T')[0]
    });

    useEffect(() => {
        // Fetch categories on mount
        fetch('/api/v1/lawyer/accounting/categories', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => setCategories(data.data));
    }, []);

    const handleSubmit = async (e) => {
        e.preventDefault();
        
        const response = await fetch('/api/v1/lawyer/accounting/transactions', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
        });
        
        const result = await response.json();
        console.log(result);
    };

    return (
        <form onSubmit={handleSubmit}>
            <select 
                value={formData.category}
                onChange={(e) => setFormData({...formData, category: e.target.value})}
            >
                <option value="">Select Category</option>
                {Object.entries(categories).map(([value, label]) => (
                    <option key={value} value={value}>{label}</option>
                ))}
            </select>
            
            {/* Other form fields */}
            
            <button type="submit">Create Transaction</button>
        </form>
    );
}
```

---

## Benefits

✅ **Type Safety:** فقط القيم الصحيحة مسموح بها

✅ **Consistency:** نفس القيم في كل التطبيق

✅ **Translations:** ترجمات تلقائية حسب اللغة

✅ **Validation:** التحقق التلقائي من القيم

✅ **Maintainability:** سهولة إضافة أو تعديل الفئات

---

## Notes

- الـ `category` field اختياري (nullable)
- إذا لم يتم تحديد category، سيتم حفظ `null`
- الترجمات تتغير تلقائياً حسب لغة التطبيق
- يمكن إضافة فئات جديدة بسهولة في الـ Enum
