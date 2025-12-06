<?php

return [
    // Page titles
    'subscriptions_management' => 'إدارة الباقات',
    'add_subscription' => 'إضافة باقة',
    'create_subscription' => 'إنشاء باقة',
    'edit_subscription' => 'تعديل الباقة',
    'view_subscription' => 'عرض الباقة',
    'subscription_details' => 'تفاصيل الباقة',
    'update_subscription' => 'تحديث الباقة',
    'delete_subscription' => 'حذف الباقة',
    
    // Fields
    'name' => 'الاسم',
    'name_english' => 'الاسم (إنجليزي)',
    'name_arabic' => 'الاسم (عربي)',
    'number_of_months' => 'عدد الأشهر',
    'months' => 'شهر',
    'activation' => 'التفعيل',
    'active' => 'نشط',
    'inactive' => 'غير نشط',
    'all' => 'الكل',
    'created_at' => 'تاريخ الإنشاء',
    
    // Placeholders
    'enter_subscription_name_english' => 'أدخل اسم الباقة بالإنجليزية',
    'enter_subscription_name_arabic' => 'أدخل اسم الباقة بالعربية',
    'enter_number_of_months' => 'أدخل عدد الأشهر',
    'search_by_name' => 'البحث بالاسم',
    
    // Messages
    'created_successfully' => 'تم إنشاء الباقة بنجاح',
    'updated_successfully' => 'تم تحديث الباقة بنجاح',
    'deleted_successfully' => 'تم حذف الباقة بنجاح',
    'creating_subscription' => 'جاري إنشاء الباقة...',
    'updating_subscription' => 'جاري تحديث الباقة...',
    'error_creating' => 'خطأ في إنشاء الباقة',
    'error_updating' => 'خطأ في تحديث الباقة',
    'error_deleting' => 'خطأ في حذف الباقة',
    'error_saving' => 'خطأ في حفظ الباقة',
    'not_found' => 'الباقة غير موجودة',
    'no_subscriptions_found' => 'لا توجد باقات',
    
    // Validation
    'validation' => [
        'name_en_required' => 'الاسم (إنجليزي) مطلوب',
        'name_ar_required' => 'الاسم (عربي) مطلوب',
        'name_unique' => 'اسم الباقة موجود مسبقاً',
        'number_of_months_required' => 'عدد الأشهر مطلوب',
        'number_of_months_integer' => 'عدد الأشهر يجب أن يكون رقماً',
        'number_of_months_min' => 'عدد الأشهر يجب أن يكون على الأقل 1',
        'number_of_months_max' => 'عدد الأشهر لا يمكن أن يتجاوز 120',
    ],
    
    // Confirmations
    'confirm_delete' => 'تأكيد الحذف',
    'delete_confirmation' => 'هل أنت متأكد من حذف هذه الباقة؟',
    'cancel' => 'إلغاء',
    
    // Hints
    'months_hint' => 'أدخل رقم بين 1 و 120',
    'validation_errors' => 'أخطاء التحقق',
];
