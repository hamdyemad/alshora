# Appointment Observer Documentation

## Overview
The `AppointmentObserver` automatically handles notifications and transactions when appointment status changes. This eliminates the need for manual notification sending in controllers and ensures consistent behavior across the application.

## Location
`app/Observers/AppointmentObserver.php`

## Registration
The observer is registered in `app/Providers/AppServiceProvider.php`:
```php
\App\Models\Appointment::observe(\App\Observers\AppointmentObserver::class);
```

## Functionality

### Automatic Status Change Detection
The observer listens to the `updated` event on the `Appointment` model and checks if the `status` field has changed using Laravel's `isDirty()` method.

### Status-Specific Actions

#### 1. Approved Status
**Trigger:** When appointment status changes to `approved`

**Actions:**
- Sends Firebase notification to customer
- Notification includes:
  - Lawyer name
  - Appointment date
  - Appointment time
  - Status: approved

**Notification Method:**
```php
FirebaseNotificationService::sendAppointmentApprovedNotification($customer, $appointment, $lawyer)
```

---

#### 2. Rejected Status
**Trigger:** When appointment status changes to `rejected`

**Actions:**
- Sends Firebase notification to customer
- Notification includes:
  - Lawyer name
  - Appointment date
  - Rejection reason (from `cancellation_reason` field)
  - Status: rejected

**Notification Method:**
```php
FirebaseNotificationService::sendAppointmentRejectedNotification($customer, $appointment, $lawyer, $reason)
```

---

#### 3. Completed Status
**Trigger:** When appointment status changes to `completed`

**Actions:**
1. **Creates Transaction Record** (if not exists):
   - Type: `income`
   - Amount: Lawyer's `consultation_price`
   - Category: `consultation`
   - Description: Translated message with customer name and date
   - Transaction date: Current timestamp
   - Linked to appointment via `appointment_id`

2. **Sends Firebase Notification** to customer:
   - Status update notification
   - Type: completed

**Transaction Creation Logic:**
- Checks if transaction already exists for this appointment
- Only creates transaction if amount > 0
- Prevents duplicate transactions

**Notification Method:**
```php
FirebaseNotificationService::sendAppointmentStatusUpdate($customer, $appointment, 'completed')
```

---

#### 4. Cancelled Status
**Trigger:** When appointment status changes to `cancelled`

**Actions:**
- Sends Firebase notification to lawyer
- Notification includes:
  - Appointment date
  - Status: cancelled

**Notification Method:**
```php
FirebaseNotificationService::sendAppointmentStatusUpdate($lawyer, $appointment, 'cancelled')
```

---

### Deleted Event
**Trigger:** When appointment is deleted

**Actions:**
- Automatically deletes associated transaction record
- Maintains data integrity

---

## Benefits

### 1. Separation of Concerns
- Controllers focus on business logic and validation
- Observer handles side effects (notifications, transactions)
- Cleaner, more maintainable code

### 2. Consistency
- All status changes trigger the same actions
- No risk of forgetting to send notifications
- Centralized logic for all appointment updates

### 3. Automatic Execution
- Works regardless of how status is changed:
  - Via API endpoints
  - Via admin panel
  - Via direct model updates
  - Via Eloquent queries

### 4. Error Handling
- Each action wrapped in try-catch
- Errors logged but don't break the update
- Graceful degradation if notifications fail

---

## Code Flow Example

### Scenario: Lawyer Approves Appointment

1. **Controller** (`AppointmentController::approve()`):
   ```php
   $this->appointmentService->updateAppointment($appointment, ['status' => 'approved']);
   ```

2. **Service** (`AppointmentService::updateAppointment()`):
   ```php
   return $this->appointmentRepository->update($appointment, $data);
   ```

3. **Repository** (`AppointmentRepository::update()`):
   ```php
   $appointment->update($data); // This triggers the observer
   ```

4. **Observer** (`AppointmentObserver::updated()`):
   ```php
   // Detects status changed to 'approved'
   $this->handleApproved($appointment);
   // Sends notification automatically
   ```

5. **Result**:
   - Appointment status updated ✅
   - Customer receives notification ✅
   - No manual notification code in controller ✅

---

## Transaction Creation Logic

### When Appointment is Completed

```php
protected function handleCompleted(Appointment $appointment): void
{
    // Check if transaction already exists
    $existingTransaction = LawyerTransaction::where('appointment_id', $appointment->id)->first();
    
    if (!$existingTransaction && $appointment->lawyer) {
        $amount = $appointment->lawyer->consultation_price ?? 0;
        
        if ($amount > 0) {
            LawyerTransaction::create([
                'lawyer_id' => $appointment->lawyer_id,
                'appointment_id' => $appointment->id,
                'type' => 'income',
                'amount' => $amount,
                'category' => 'consultation',
                'description' => __('appointment.transaction_description', [
                    'customer' => $appointment->customer->getTranslation('name', app()->getLocale()),
                    'date' => $appointment->appointment_date->format('Y-m-d'),
                ]),
                'transaction_date' => now(),
            ]);
        }
    }
}
```

### Safeguards
- ✅ Checks for existing transaction (prevents duplicates)
- ✅ Validates amount > 0
- ✅ Loads lawyer relationship if needed
- ✅ Uses translated description
- ✅ Links to appointment via `appointment_id`

---

## Error Handling

All notification and transaction operations are wrapped in try-catch blocks:

```php
try {
    // Send notification or create transaction
} catch (\Exception $e) {
    Log::error('Failed to handle appointment status: ' . $e->getMessage());
}
```

**Benefits:**
- Appointment update succeeds even if notification fails
- Errors are logged for debugging
- Application remains stable

---

## Logging

The observer logs important events:

```php
Log::info('Appointment status changed', [
    'appointment_id' => $appointment->id,
    'old_status' => $oldStatus,
    'new_status' => $newStatus
]);

Log::info('Transaction created for completed appointment', [
    'appointment_id' => $appointment->id,
    'amount' => $amount
]);
```

---

## Testing Considerations

### Manual Testing
1. Change appointment status via API
2. Check logs for observer execution
3. Verify notification sent
4. Verify transaction created (for completed status)

### Unit Testing
```php
// Test that observer sends notification on status change
$appointment->update(['status' => 'approved']);
// Assert notification was sent
```

---

## Related Files

- **Observer**: `app/Observers/AppointmentObserver.php`
- **Model**: `app/Models/Appointment.php`
- **Service**: `app/Services/FirebaseNotificationService.php`
- **Transaction Model**: `app/Models/LawyerTransaction.php`
- **Registration**: `app/Providers/AppServiceProvider.php`
- **Translations**: 
  - `lang/ar/appointment.php`
  - `lang/en/appointment.php`

---

## Migration from Controller-Based Notifications

### Before (Controller)
```php
// In AppointmentController::approve()
$this->appointmentService->updateAppointment($appointment, ['status' => 'approved']);

// Manual notification
if ($appointment->customer && $appointment->customer->user) {
    $this->firebaseService->sendAppointmentApprovedNotification(
        $appointment->customer->user,
        $appointment,
        $lawyer
    );
}
```

### After (Observer)
```php
// In AppointmentController::approve()
$this->appointmentService->updateAppointment($appointment, ['status' => 'approved']);
// Notification sent automatically by observer ✅
```

**Result:**
- ✅ Less code in controllers
- ✅ Automatic notifications
- ✅ Consistent behavior
- ✅ Easier to maintain

---

## Future Enhancements

Potential additions to the observer:

1. **Email Notifications**: Send email alongside Firebase notifications
2. **SMS Notifications**: Send SMS for important status changes
3. **Webhook Triggers**: Notify external systems of status changes
4. **Analytics Events**: Track appointment lifecycle events
5. **Audit Trail**: Log all status changes with timestamps and user info

---

## Troubleshooting

### Notifications Not Sent
1. Check if observer is registered in `AppServiceProvider`
2. Verify Firebase configuration
3. Check logs for error messages
4. Ensure user has valid FCM token

### Transactions Not Created
1. Check if appointment status is `completed`
2. Verify lawyer has `consultation_price` set
3. Check for existing transaction (prevents duplicates)
4. Review logs for errors

### Observer Not Firing
1. Ensure status is actually changing (use `isDirty()`)
2. Check if observer is registered
3. Verify model events are not disabled
4. Check for transaction rollbacks
