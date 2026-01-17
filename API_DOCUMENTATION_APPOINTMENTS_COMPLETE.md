# Appointment Complete API Documentation

## Overview
This document describes the appointment completion functionality for lawyers. When a lawyer completes an appointment, the system automatically creates a transaction record in the accounting system.

## Endpoint

### Complete Appointment
**POST** `/api/v1/lawyer/appointments/{id}/complete`

Complete an appointment and automatically create a transaction record.

#### Authentication
- Requires: `Bearer Token` (Sanctum)
- Middleware: `auth:sanctum`, `lawyer`

#### URL Parameters
- `id` (integer, required): The appointment ID

#### Request Body
No request body required.

#### Response

**Success Response (200 OK)**
```json
{
    "status": true,
    "message": "تم إكمال الموعد بنجاح وتسجيل المعاملة",
    "data": {
        "id": 11,
        "appointment_date": "2025-12-20",
        "day": "saturday",
        "period": "morning",
        "time_slot": "06:00",
        "consultation_type": "office",
        "notes": "pariatur irure",
        "status": "completed",
        "cancellation_reason": null,
        "customer": {
            "id": 3,
            "name": "حمدى",
            "name_en": "www",
            "name_ar": "حمدى",
            "email": "customer@example.com",
            "phone": "1090563070",
            "address": "123 Main St",
            "logo": "http://127.0.0.1:8000/storage/customers/3/logo/example.jpg"
        },
        "created_at": "2025-12-14 21:59:33",
        "updated_at": "2025-12-14 22:30:00"
    },
    "errors": []
}
```

**Error Responses**

*Appointment Not Found (404)*
```json
{
    "status": false,
    "message": "الموعد غير موجود",
    "data": [],
    "errors": []
}
```

*Unauthorized (403)*
```json
{
    "status": false,
    "message": "غير مصرح لك بتنفيذ هذا الإجراء",
    "data": [],
    "errors": []
}
```

*Already Completed (400)*
```json
{
    "status": false,
    "message": "الموعد مكتمل بالفعل",
    "data": [],
    "errors": []
}
```

*Invalid Status (400)*
```json
{
    "status": false,
    "message": "لا يمكن إكمال الموعد إلا إذا كان معلقاً أو مقبولاً",
    "data": [],
    "errors": []
}
```

## Business Logic

### Completion Rules
1. Only the lawyer who owns the appointment can complete it
2. Appointment must be in `pending` or `approved` status
3. Cannot complete an already `completed` appointment
4. Cannot complete a `rejected` or `cancelled` appointment

### Automatic Transaction Creation
When an appointment is completed, the system automatically:
1. Creates a transaction record with:
   - Type: `income`
   - Amount: Lawyer's consultation price
   - Category: `consultation`
   - Description: "استشارة مع العميل {customer_name} بتاريخ {date}"
   - Transaction date: Current timestamp
   - Linked to the appointment ID

### Status Protection
Once an appointment is marked as `completed`:
- Status cannot be changed via `PUT /appointments/{id}/status`
- Appointment is locked and cannot be modified
- Transaction record is permanent

## Updated Endpoints

### Get Lawyer Appointments
**GET** `/api/v1/lawyer/appointments`

Now returns appointments without lawyer data (since the lawyer is the authenticated user) and includes full customer details.

**Response Changes:**
- ✅ Removed `lawyer` object from response
- ✅ Enhanced `customer` object with:
  - `email`
  - `address`
  - `logo` (profile image URL)

### Update Appointment Status
**PUT** `/api/v1/lawyer/appointments/{id}/status`

**New Validation:**
- Cannot change status if appointment is `completed`
- Returns error: "لا يمكن تغيير حالة موعد مكتمل"

## Example Usage

### Complete an Appointment
```bash
curl -X POST "http://127.0.0.1:8000/api/v1/lawyer/appointments/11/complete" \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

### Check Transaction Created
After completing appointment #11, check the accounting transactions:
```bash
curl -X GET "http://127.0.0.1:8000/api/v1/lawyer/accounting/transactions" \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

You should see a new transaction with:
- `appointment_id`: 11
- `type`: "income"
- `category`: "consultation"
- `amount`: {lawyer's consultation_price}

## Translation Keys

### Arabic (ar)
- `appointment.already_completed`: "الموعد مكتمل بالفعل"
- `appointment.cannot_complete_invalid_status`: "لا يمكن إكمال الموعد إلا إذا كان معلقاً أو مقبولاً"
- `appointment.completed_successfully`: "تم إكمال الموعد بنجاح وتسجيل المعاملة"
- `appointment.transaction_description`: "استشارة مع العميل :customer بتاريخ :date"
- `appointment.cannot_change_completed_status`: "لا يمكن تغيير حالة موعد مكتمل"

### English (en)
- `appointment.already_completed`: "Appointment is already completed"
- `appointment.cannot_complete_invalid_status`: "Can only complete pending or approved appointments"
- `appointment.completed_successfully`: "Appointment completed successfully and transaction recorded"
- `appointment.transaction_description`: "Consultation with customer :customer on :date"
- `appointment.cannot_change_completed_status`: "Cannot change status of a completed appointment"

## Database Changes

### Appointments Table
No schema changes required. Uses existing `status` column with new value: `completed`

### Lawyer Transactions Table
New records created automatically with:
- `appointment_id`: Links to the completed appointment
- `type`: 'income'
- `category`: 'consultation'
- `amount`: From lawyer's `consultation_price`
- `transaction_date`: Timestamp of completion

## Notes

1. **Firebase Notifications**: The system attempts to send a completion notification to the customer. If the notification fails, the appointment is still marked as completed.

2. **Transaction Integrity**: The transaction is created within the same service method to ensure data consistency.

3. **Idempotency**: Attempting to complete an already completed appointment returns a 400 error without creating duplicate transactions.

4. **Audit Trail**: Both the appointment and transaction records maintain timestamps for audit purposes.
