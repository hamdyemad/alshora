<?php

return [
    // Page titles
    'subscriptions_management' => 'Subscriptions Management',
    'add_subscription' => 'Add Subscription',
    'create_subscription' => 'Create Subscription',
    'edit_subscription' => 'Edit Subscription',
    'view_subscription' => 'View Subscription',
    'subscription_details' => 'Subscription Details',
    'update_subscription' => 'Update Subscription',
    'delete_subscription' => 'Delete Subscription',
    
    // Fields
    'name' => 'Name',
    'name_english' => 'Name (English)',
    'name_arabic' => 'Name (Arabic)',
    'number_of_months' => 'Number of Months',
    'months' => 'Months',
    'activation' => 'Activation',
    'active' => 'Active',
    'inactive' => 'Inactive',
    'all' => 'All',
    'created_at' => 'Created At',
    
    // Placeholders
    'enter_subscription_name_english' => 'Enter subscription name in English',
    'enter_subscription_name_arabic' => 'Enter subscription name in Arabic',
    'enter_number_of_months' => 'Enter number of months',
    'search_by_name' => 'Search by name',
    
    // Messages
    'created_successfully' => 'Subscription created successfully',
    'updated_successfully' => 'Subscription updated successfully',
    'deleted_successfully' => 'Subscription deleted successfully',
    'creating_subscription' => 'Creating Subscription...',
    'updating_subscription' => 'Updating Subscription...',
    'error_creating' => 'Error creating subscription',
    'error_updating' => 'Error updating subscription',
    'error_deleting' => 'Error deleting subscription',
    'error_saving' => 'Error saving subscription',
    'not_found' => 'Subscription not found',
    'no_subscriptions_found' => 'No subscriptions found',
    
    // Validation
    'validation' => [
        'name_en_required' => 'Name (English) is required',
        'name_ar_required' => 'Name (Arabic) is required',
        'name_unique' => 'This subscription name already exists',
        'number_of_months_required' => 'Number of months is required',
        'number_of_months_integer' => 'Number of months must be a number',
        'number_of_months_min' => 'Number of months must be at least 1',
        'number_of_months_max' => 'Number of months cannot exceed 120',
    ],
    
    // Confirmations
    'confirm_delete' => 'Confirm Delete',
    'delete_confirmation' => 'Are you sure you want to delete this subscription?',
    'cancel' => 'Cancel',
    
    // Hints
    'months_hint' => 'Enter a number between 1 and 120',
    'validation_errors' => 'Validation Errors',
];
