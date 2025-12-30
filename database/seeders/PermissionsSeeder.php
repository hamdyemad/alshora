<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permession;
use App\Models\Language;
use Illuminate\Support\Facades\DB;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('role_permession')->truncate();
            DB::table('roles')->truncate();
            DB::table('permessions')->truncate();

        $languages = Language::all();
        
        $permissions = [
            // Dashboard
            ['key' => 'dashboard.view', 'translations' => ['name' => ['en' => 'View Dashboard', 'ar' => 'عرض لوحة التحكم'], 'group_by' => ['en' => 'Dashboard', 'ar' => 'لوحة التحكم']]],
            
            // Roles
            ['key' => 'roles.view', 'translations' => ['name' => ['en' => 'View Roles', 'ar' => 'عرض الأدوار'], 'group_by' => ['en' => 'Roles', 'ar' => 'الأدوار']]],
            ['key' => 'roles.create', 'translations' => ['name' => ['en' => 'Create Roles', 'ar' => 'إنشاء الأدوار'], 'group_by' => ['en' => 'Roles', 'ar' => 'الأدوار']]],
            ['key' => 'roles.edit', 'translations' => ['name' => ['en' => 'Edit Roles', 'ar' => 'تعديل الأدوار'], 'group_by' => ['en' => 'Roles', 'ar' => 'الأدوار']]],
            ['key' => 'roles.delete', 'translations' => ['name' => ['en' => 'Delete Roles', 'ar' => 'حذف الأدوار'], 'group_by' => ['en' => 'Roles', 'ar' => 'الأدوار']]],
            
            // Admins
            ['key' => 'admins.view', 'translations' => ['name' => ['en' => 'View Admins', 'ar' => 'عرض المسؤولين'], 'group_by' => ['en' => 'Admins', 'ar' => 'المسؤولين']]],
            ['key' => 'admins.create', 'translations' => ['name' => ['en' => 'Create Admins', 'ar' => 'إنشاء المسؤولين'], 'group_by' => ['en' => 'Admins', 'ar' => 'المسؤولين']]],
            ['key' => 'admins.edit', 'translations' => ['name' => ['en' => 'Edit Admins', 'ar' => 'تعديل المسؤولين'], 'group_by' => ['en' => 'Admins', 'ar' => 'المسؤولين']]],
            ['key' => 'admins.delete', 'translations' => ['name' => ['en' => 'Delete Admins', 'ar' => 'حذف المسؤولين'], 'group_by' => ['en' => 'Admins', 'ar' => 'المسؤولين']]],
            
            // Countries
            ['key' => 'countries.view', 'translations' => ['name' => ['en' => 'View Countries', 'ar' => 'عرض الدول'], 'group_by' => ['en' => 'Countries', 'ar' => 'الدول']]],
            ['key' => 'countries.create', 'translations' => ['name' => ['en' => 'Create Countries', 'ar' => 'إنشاء الدول'], 'group_by' => ['en' => 'Countries', 'ar' => 'الدول']]],
            ['key' => 'countries.edit', 'translations' => ['name' => ['en' => 'Edit Countries', 'ar' => 'تعديل الدول'], 'group_by' => ['en' => 'Countries', 'ar' => 'الدول']]],
            ['key' => 'countries.delete', 'translations' => ['name' => ['en' => 'Delete Countries', 'ar' => 'حذف الدول'], 'group_by' => ['en' => 'Countries', 'ar' => 'الدول']]],
            
            // Cities
            ['key' => 'cities.view', 'translations' => ['name' => ['en' => 'View Cities', 'ar' => 'عرض المدن'], 'group_by' => ['en' => 'Cities', 'ar' => 'المدن']]],
            ['key' => 'cities.create', 'translations' => ['name' => ['en' => 'Create Cities', 'ar' => 'إنشاء المدن'], 'group_by' => ['en' => 'Cities', 'ar' => 'المدن']]],
            ['key' => 'cities.edit', 'translations' => ['name' => ['en' => 'Edit Cities', 'ar' => 'تعديل المدن'], 'group_by' => ['en' => 'Cities', 'ar' => 'المدن']]],
            ['key' => 'cities.delete', 'translations' => ['name' => ['en' => 'Delete Cities', 'ar' => 'حذف المدن'], 'group_by' => ['en' => 'Cities', 'ar' => 'المدن']]],
            
            // Regions
            ['key' => 'regions.view', 'translations' => ['name' => ['en' => 'View Regions', 'ar' => 'عرض المناطق'], 'group_by' => ['en' => 'Regions', 'ar' => 'المناطق']]],
            ['key' => 'regions.create', 'translations' => ['name' => ['en' => 'Create Regions', 'ar' => 'إنشاء المناطق'], 'group_by' => ['en' => 'Regions', 'ar' => 'المناطق']]],
            ['key' => 'regions.edit', 'translations' => ['name' => ['en' => 'Edit Regions', 'ar' => 'تعديل المناطق'], 'group_by' => ['en' => 'Regions', 'ar' => 'المناطق']]],
            ['key' => 'regions.delete', 'translations' => ['name' => ['en' => 'Delete Regions', 'ar' => 'حذف المناطق'], 'group_by' => ['en' => 'Regions', 'ar' => 'المناطق']]],

            // SubRegions
            ['key' => 'subregions.view', 'translations' => ['name' => ['en' => 'View SubRegions', 'ar' => 'عرض المناطق الفرعية'], 'group_by' => ['en' => 'SubRegions', 'ar' => 'المناطق الفرعية']]],
            ['key' => 'subregions.create', 'translations' => ['name' => ['en' => 'Create SubRegions', 'ar' => 'إنشاء المناطق الفرعية'], 'group_by' => ['en' => 'SubRegions', 'ar' => 'المناطق الفرعية']]],
            ['key' => 'subregions.edit', 'translations' => ['name' => ['en' => 'Edit SubRegions', 'ar' => 'تعديل المناطق الفرعية'], 'group_by' => ['en' => 'SubRegions', 'ar' => 'المناطق الفرعية']]],
            ['key' => 'subregions.delete', 'translations' => ['name' => ['en' => 'Delete SubRegions', 'ar' => 'حذف المناطق الفرعية'], 'group_by' => ['en' => 'SubRegions', 'ar' => 'المناطق الفرعية']]],
            
            // Lawyers
            ['key' => 'lawyers.view', 'translations' => ['name' => ['en' => 'View Lawyers', 'ar' => 'عرض المحامين'], 'group_by' => ['en' => 'Lawyers', 'ar' => 'المحامين']]],
            ['key' => 'lawyers.create', 'translations' => ['name' => ['en' => 'Create Lawyers', 'ar' => 'إنشاء المحامين'], 'group_by' => ['en' => 'Lawyers', 'ar' => 'المحامين']]],
            ['key' => 'lawyers.edit', 'translations' => ['name' => ['en' => 'Edit Lawyers', 'ar' => 'تعديل المحامين'], 'group_by' => ['en' => 'Lawyers', 'ar' => 'المحامين']]],
            ['key' => 'lawyers.delete', 'translations' => ['name' => ['en' => 'Delete Lawyers', 'ar' => 'حذف المحامين'], 'group_by' => ['en' => 'Lawyers', 'ar' => 'المحامين']]],
            
            // Customers
            ['key' => 'customers.view', 'translations' => ['name' => ['en' => 'View Customers', 'ar' => 'عرض العملاء'], 'group_by' => ['en' => 'Customers', 'ar' => 'العملاء']]],
            ['key' => 'customers.create', 'translations' => ['name' => ['en' => 'Create Customers', 'ar' => 'إنشاء العملاء'], 'group_by' => ['en' => 'Customers', 'ar' => 'العملاء']]],
            ['key' => 'customers.edit', 'translations' => ['name' => ['en' => 'Edit Customers', 'ar' => 'تعديل العملاء'], 'group_by' => ['en' => 'Customers', 'ar' => 'العملاء']]],
            ['key' => 'customers.delete', 'translations' => ['name' => ['en' => 'Delete Customers', 'ar' => 'حذف العملاء'], 'group_by' => ['en' => 'Customers', 'ar' => 'العملاء']]],
            
            // Subscriptions
            ['key' => 'subscriptions.view', 'translations' => ['name' => ['en' => 'View Subscriptions', 'ar' => 'عرض الاشتراكات'], 'group_by' => ['en' => 'Subscriptions', 'ar' => 'الاشتراكات']]],
            ['key' => 'subscriptions.create', 'translations' => ['name' => ['en' => 'Create Subscriptions', 'ar' => 'إنشاء الاشتراكات'], 'group_by' => ['en' => 'Subscriptions', 'ar' => 'الاشتراكات']]],
            ['key' => 'subscriptions.edit', 'translations' => ['name' => ['en' => 'Edit Subscriptions', 'ar' => 'تعديل الاشتراكات'], 'group_by' => ['en' => 'Subscriptions', 'ar' => 'الاشتراكات']]],
            ['key' => 'subscriptions.delete', 'translations' => ['name' => ['en' => 'Delete Subscriptions', 'ar' => 'حذف الاشتراكات'], 'group_by' => ['en' => 'Subscriptions', 'ar' => 'الاشتراكات']]],
            
            // News
            ['key' => 'news.view', 'translations' => ['name' => ['en' => 'View News', 'ar' => 'عرض الأخبار'], 'group_by' => ['en' => 'News', 'ar' => 'الأخبار']]],
            ['key' => 'news.create', 'translations' => ['name' => ['en' => 'Create News', 'ar' => 'إنشاء الأخبار'], 'group_by' => ['en' => 'News', 'ar' => 'الأخبار']]],
            ['key' => 'news.edit', 'translations' => ['name' => ['en' => 'Edit News', 'ar' => 'تعديل الأخبار'], 'group_by' => ['en' => 'News', 'ar' => 'الأخبار']]],
            ['key' => 'news.delete', 'translations' => ['name' => ['en' => 'Delete News', 'ar' => 'حذف الأخبار'], 'group_by' => ['en' => 'News', 'ar' => 'الأخبار']]],
            
            // Agendas
            ['key' => 'agendas.view', 'translations' => ['name' => ['en' => 'View Agendas', 'ar' => 'عرض الأجندات'], 'group_by' => ['en' => 'Agendas', 'ar' => 'الأجندات']]],
            ['key' => 'agendas.delete', 'translations' => ['name' => ['en' => 'Delete Agendas', 'ar' => 'حذف الأجندات'], 'group_by' => ['en' => 'Agendas', 'ar' => 'الأجندات']]],
            
            // Preparer Agendas
            ['key' => 'preparer-agendas.view', 'translations' => ['name' => ['en' => 'View Preparer Agendas', 'ar' => 'عرض أجندات المحضرين'], 'group_by' => ['en' => 'Preparer Agendas', 'ar' => 'أجندات المحضرين']]],
            ['key' => 'preparer-agendas.delete', 'translations' => ['name' => ['en' => 'Delete Preparer Agendas', 'ar' => 'حذف أجندات المحضرين'], 'group_by' => ['en' => 'Preparer Agendas', 'ar' => 'أجندات المحضرين']]],
            
            // Sections of Laws
            ['key' => 'sections-of-laws.view', 'translations' => ['name' => ['en' => 'View Sections of Laws', 'ar' => 'عرض أقسام القوانين'], 'group_by' => ['en' => 'Sections of Laws', 'ar' => 'أقسام القوانين']]],
            ['key' => 'sections-of-laws.create', 'translations' => ['name' => ['en' => 'Create Sections of Laws', 'ar' => 'إنشاء أقسام القوانين'], 'group_by' => ['en' => 'Sections of Laws', 'ar' => 'أقسام القوانين']]],
            ['key' => 'sections-of-laws.edit', 'translations' => ['name' => ['en' => 'Edit Sections of Laws', 'ar' => 'تعديل أقسام القوانين'], 'group_by' => ['en' => 'Sections of Laws', 'ar' => 'أقسام القوانين']]],
            ['key' => 'sections-of-laws.delete', 'translations' => ['name' => ['en' => 'Delete Sections of Laws', 'ar' => 'حذف أقسام القوانين'], 'group_by' => ['en' => 'Sections of Laws', 'ar' => 'أقسام القوانين']]],

            // Instructions
            ['key' => 'instructions.view', 'translations' => ['name' => ['en' => 'View Instructions', 'ar' => 'عرض التعليمات'], 'group_by' => ['en' => 'Instructions', 'ar' => 'التعليمات']]],
            ['key' => 'instructions.create', 'translations' => ['name' => ['en' => 'Create Instructions', 'ar' => 'إنشاء التعليمات'], 'group_by' => ['en' => 'Instructions', 'ar' => 'التعليمات']]],
            ['key' => 'instructions.edit', 'translations' => ['name' => ['en' => 'Edit Instructions', 'ar' => 'تعديل التعليمات'], 'group_by' => ['en' => 'Instructions', 'ar' => 'التعليمات']]],
            ['key' => 'instructions.delete', 'translations' => ['name' => ['en' => 'Delete Instructions', 'ar' => 'حذف التعليمات'], 'group_by' => ['en' => 'Instructions', 'ar' => 'التعليمات']]],
            
            // Branches of Laws
            ['key' => 'branches-of-laws.view', 'translations' => ['name' => ['en' => 'View Branches of Laws', 'ar' => 'عرض فروع القوانين'], 'group_by' => ['en' => 'Branches of Laws', 'ar' => 'فروع القوانين']]],
            ['key' => 'branches-of-laws.create', 'translations' => ['name' => ['en' => 'Create Branches of Laws', 'ar' => 'إنشاء فروع القوانين'], 'group_by' => ['en' => 'Branches of Laws', 'ar' => 'فروع القوانين']]],
            ['key' => 'branches-of-laws.edit', 'translations' => ['name' => ['en' => 'Edit Branches of Laws', 'ar' => 'تعديل فروع القوانين'], 'group_by' => ['en' => 'Branches of Laws', 'ar' => 'فروع القوانين']]],
            ['key' => 'branches-of-laws.delete', 'translations' => ['name' => ['en' => 'Delete Branches of Laws', 'ar' => 'حذف فروع القوانين'], 'group_by' => ['en' => 'Branches of Laws', 'ar' => 'فروع القوانين']]],
            
            // Laws
            ['key' => 'laws.view', 'translations' => ['name' => ['en' => 'View Laws', 'ar' => 'عرض القوانين'], 'group_by' => ['en' => 'Laws', 'ar' => 'القوانين']]],
            ['key' => 'laws.create', 'translations' => ['name' => ['en' => 'Create Laws', 'ar' => 'إنشاء القوانين'], 'group_by' => ['en' => 'Laws', 'ar' => 'القوانين']]],
            ['key' => 'laws.edit', 'translations' => ['name' => ['en' => 'Edit Laws', 'ar' => 'تعديل القوانين'], 'group_by' => ['en' => 'Laws', 'ar' => 'القوانين']]],
            ['key' => 'laws.delete', 'translations' => ['name' => ['en' => 'Delete Laws', 'ar' => 'حذف القوانين'], 'group_by' => ['en' => 'Laws', 'ar' => 'القوانين']]],
            
            // Drafting Contracts
            ['key' => 'drafting-contracts.view', 'translations' => ['name' => ['en' => 'View Drafting Contracts', 'ar' => 'عرض صياغة العقود'], 'group_by' => ['en' => 'Drafting Contracts', 'ar' => 'صياغة العقود']]],
            ['key' => 'drafting-contracts.create', 'translations' => ['name' => ['en' => 'Create Drafting Contracts', 'ar' => 'إنشاء صياغة العقود'], 'group_by' => ['en' => 'Drafting Contracts', 'ar' => 'صياغة العقود']]],
            ['key' => 'drafting-contracts.edit', 'translations' => ['name' => ['en' => 'Edit Drafting Contracts', 'ar' => 'تعديل صياغة العقود'], 'group_by' => ['en' => 'Drafting Contracts', 'ar' => 'صياغة العقود']]],
            ['key' => 'drafting-contracts.delete', 'translations' => ['name' => ['en' => 'Delete Drafting Contracts', 'ar' => 'حذف صياغة العقود'], 'group_by' => ['en' => 'Drafting Contracts', 'ar' => 'صياغة العقود']]],
            
            // Drafting Lawsuits
            ['key' => 'drafting-lawsuits.view', 'translations' => ['name' => ['en' => 'View Drafting Lawsuits', 'ar' => 'عرض صياغة الدعاوى'], 'group_by' => ['en' => 'Drafting Lawsuits', 'ar' => 'صياغة الدعاوى']]],
            ['key' => 'drafting-lawsuits.create', 'translations' => ['name' => ['en' => 'Create Drafting Lawsuits', 'ar' => 'إنشاء صياغة الدعاوى'], 'group_by' => ['en' => 'Drafting Lawsuits', 'ar' => 'صياغة الدعاوى']]],
            ['key' => 'drafting-lawsuits.edit', 'translations' => ['name' => ['en' => 'Edit Drafting Lawsuits', 'ar' => 'تعديل صياغة الدعاوى'], 'group_by' => ['en' => 'Drafting Lawsuits', 'ar' => 'صياغة الدعاوى']]],
            ['key' => 'drafting-lawsuits.delete', 'translations' => ['name' => ['en' => 'Delete Drafting Lawsuits', 'ar' => 'حذف صياغة الدعاوى'], 'group_by' => ['en' => 'Drafting Lawsuits', 'ar' => 'صياغة الدعاوى']]],
            
            // Measures
            ['key' => 'measures.view', 'translations' => ['name' => ['en' => 'View Measures', 'ar' => 'عرض الإجراءات'], 'group_by' => ['en' => 'Measures', 'ar' => 'الإجراءات']]],
            ['key' => 'measures.create', 'translations' => ['name' => ['en' => 'Create Measures', 'ar' => 'إنشاء الإجراءات'], 'group_by' => ['en' => 'Measures', 'ar' => 'الإجراءات']]],
            ['key' => 'measures.edit', 'translations' => ['name' => ['en' => 'Edit Measures', 'ar' => 'تعديل الإجراءات'], 'group_by' => ['en' => 'Measures', 'ar' => 'الإجراءات']]],
            ['key' => 'measures.delete', 'translations' => ['name' => ['en' => 'Delete Measures', 'ar' => 'حذف الإجراءات'], 'group_by' => ['en' => 'Measures', 'ar' => 'الإجراءات']]],
            
            // Store Categories
            ['key' => 'store-categories.view', 'translations' => ['name' => ['en' => 'View Store Categories', 'ar' => 'عرض فئات المتجر'], 'group_by' => ['en' => 'Store Categories', 'ar' => 'فئات المتجر']]],
            ['key' => 'store-categories.create', 'translations' => ['name' => ['en' => 'Create Store Categories', 'ar' => 'إنشاء فئات المتجر'], 'group_by' => ['en' => 'Store Categories', 'ar' => 'فئات المتجر']]],
            ['key' => 'store-categories.edit', 'translations' => ['name' => ['en' => 'Edit Store Categories', 'ar' => 'تعديل فئات المتجر'], 'group_by' => ['en' => 'Store Categories', 'ar' => 'فئات المتجر']]],
            ['key' => 'store-categories.delete', 'translations' => ['name' => ['en' => 'Delete Store Categories', 'ar' => 'حذف فئات المتجر'], 'group_by' => ['en' => 'Store Categories', 'ar' => 'فئات المتجر']]],

            // Store Products
            ['key' => 'store-products.view', 'translations' => ['name' => ['en' => 'View Store Products', 'ar' => 'عرض منتجات المتجر'], 'group_by' => ['en' => 'Store Products', 'ar' => 'منتجات المتجر']]],
            ['key' => 'store-products.create', 'translations' => ['name' => ['en' => 'Create Store Products', 'ar' => 'إنشاء منتجات المتجر'], 'group_by' => ['en' => 'Store Products', 'ar' => 'منتجات المتجر']]],
            ['key' => 'store-products.edit', 'translations' => ['name' => ['en' => 'Edit Store Products', 'ar' => 'تعديل منتجات المتجر'], 'group_by' => ['en' => 'Store Products', 'ar' => 'منتجات المتجر']]],
            ['key' => 'store-products.delete', 'translations' => ['name' => ['en' => 'Delete Store Products', 'ar' => 'حذف منتجات المتجر'], 'group_by' => ['en' => 'Store Products', 'ar' => 'منتجات المتجر']]],
            
            // Store Orders
            ['key' => 'store-orders.view', 'translations' => ['name' => ['en' => 'View Store Orders', 'ar' => 'عرض طلبات المتجر'], 'group_by' => ['en' => 'Store Orders', 'ar' => 'طلبات المتجر']]],
            ['key' => 'store-orders.edit', 'translations' => ['name' => ['en' => 'Edit Store Orders', 'ar' => 'تعديل طلبات المتجر'], 'group_by' => ['en' => 'Store Orders', 'ar' => 'طلبات المتجر']]],
            
            // Reviews
            ['key' => 'reviews.view', 'translations' => ['name' => ['en' => 'View Reviews', 'ar' => 'عرض التقييمات'], 'group_by' => ['en' => 'Reviews', 'ar' => 'التقييمات']]],
            ['key' => 'reviews.approve', 'translations' => ['name' => ['en' => 'Approve Reviews', 'ar' => 'الموافقة على التقييمات'], 'group_by' => ['en' => 'Reviews', 'ar' => 'التقييمات']]],
            ['key' => 'reviews.reject', 'translations' => ['name' => ['en' => 'Reject Reviews', 'ar' => 'رفض التقييمات'], 'group_by' => ['en' => 'Reviews', 'ar' => 'التقييمات']]],
            ['key' => 'reviews.delete', 'translations' => ['name' => ['en' => 'Delete Reviews', 'ar' => 'حذف التقييمات'], 'group_by' => ['en' => 'Reviews', 'ar' => 'التقييمات']]],
            
            // Reservations
            ['key' => 'reservations.view', 'translations' => ['name' => ['en' => 'View Reservations', 'ar' => 'عرض الحجوزات'], 'group_by' => ['en' => 'Reservations', 'ar' => 'الحجوزات']]],
            ['key' => 'reservations.edit', 'translations' => ['name' => ['en' => 'Edit Reservations', 'ar' => 'تعديل الحجوزات'], 'group_by' => ['en' => 'Reservations', 'ar' => 'الحجوزات']]],
            
            // Notifications
            ['key' => 'notifications.view', 'translations' => ['name' => ['en' => 'View Notifications', 'ar' => 'عرض الإشعارات'], 'group_by' => ['en' => 'Notifications', 'ar' => 'الإشعارات']]],
            ['key' => 'notifications.manage', 'translations' => ['name' => ['en' => 'Manage Notifications', 'ar' => 'إدارة الإشعارات'], 'group_by' => ['en' => 'Notifications', 'ar' => 'الإشعارات']]],
            
            // Hosting
            ['key' => 'hosting.view', 'translations' => ['name' => ['en' => 'View Hosting', 'ar' => 'عرض الاستضافة'], 'group_by' => ['en' => 'Hosting', 'ar' => 'الاستضافة']]],
            ['key' => 'hosting.settings', 'translations' => ['name' => ['en' => 'Hosting Settings', 'ar' => 'إعدادات الاستضافة'], 'group_by' => ['en' => 'Hosting', 'ar' => 'الاستضافة']]],
            
            // Hosting Reservations
            ['key' => 'hosting-reservations.view', 'translations' => ['name' => ['en' => 'View Hosting Reservations', 'ar' => 'عرض حجوزات الاستضافة'], 'group_by' => ['en' => 'Hosting Reservations', 'ar' => 'حجوزات الاستضافة']]],
            ['key' => 'hosting-reservations.approve', 'translations' => ['name' => ['en' => 'Approve Hosting Reservations', 'ar' => 'الموافقة على حجوزات الاستضافة'], 'group_by' => ['en' => 'Hosting Reservations', 'ar' => 'حجوزات الاستضافة']]],
            ['key' => 'hosting-reservations.reject', 'translations' => ['name' => ['en' => 'Reject Hosting Reservations', 'ar' => 'رفض حجوزات الاستضافة'], 'group_by' => ['en' => 'Hosting Reservations', 'ar' => 'حجوزات الاستضافة']]],

            // Support Messages
            ['key' => 'support-messages.view', 'translations' => ['name' => ['en' => 'View Support Messages', 'ar' => 'عرض رسائل الدعم'], 'group_by' => ['en' => 'Support Messages', 'ar' => 'رسائل الدعم']]],
            ['key' => 'support-messages.edit', 'translations' => ['name' => ['en' => 'Edit Support Messages', 'ar' => 'تعديل رسائل الدعم'], 'group_by' => ['en' => 'Support Messages', 'ar' => 'رسائل الدعم']]],
            ['key' => 'support-messages.delete', 'translations' => ['name' => ['en' => 'Delete Support Messages', 'ar' => 'حذف رسائل الدعم'], 'group_by' => ['en' => 'Support Messages', 'ar' => 'رسائل الدعم']]],
            
            // Client Agendas
            ['key' => 'client-agendas.view', 'translations' => ['name' => ['en' => 'View Client Agendas', 'ar' => 'عرض أجندة الموكلين'], 'group_by' => ['en' => 'Client Agendas', 'ar' => 'أجندة الموكلين']]],
            ['key' => 'client-agendas.delete', 'translations' => ['name' => ['en' => 'Delete Client Agendas', 'ar' => 'حذف أجندة الموكلين'], 'group_by' => ['en' => 'Client Agendas', 'ar' => 'أجندة الموكلين']]],
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        foreach ($permissions as $permissionData) {
            $permission = Permession::where('key', $permissionData['key'])->first();
            
            if (!$permission) {
                $permission = Permession::create([
                    'key' => $permissionData['key'],
                ]);
            }

            // Save translations for name and group_by
            foreach ($languages as $language) {
                if (isset($permissionData['translations']['name'][$language->code])) {
                    $permission->setTranslation('name', $language->code, $permissionData['translations']['name'][$language->code]);
                }
                if (isset($permissionData['translations']['group_by'][$language->code])) {
                    $permission->setTranslation('group_by', $language->code, $permissionData['translations']['group_by'][$language->code]);
                }
            }
            
            // Ensure permission is saved
            $permission->save();
        }

        $this->command->info('Permissions seeded successfully!');
    }
}
