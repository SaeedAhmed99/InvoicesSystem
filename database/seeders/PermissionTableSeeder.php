<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
class PermissionTableSeeder extends Seeder
{
/**
* Run the database seeds.
*
* @return void
*/
    public function run()
    {
        $permissions = [
            'الفواتير',
            'قائمة الفواتير',
            'الفواتير المدفوعة',
            'الفواتير المدفوعة جزئيا',
            'الفواتير الغير مدفوعة',
            'ارشيف الفواتير',
            'التقارير',
            'تقرير الفواتير',
            'تقرير العملاء',
            'المستخدمين',
            'قائمة المستخدمين',
            'صلاحيات المستخدمين',
            'الاعدادات',
            'المنتجات',
            'الاقسام',
            'اضافة فاتورة',
            'حذف الفاتورة',
            'تصدير EXCEL',
            'تغير حالة الدفع',
            'تعديل الفاتورة',
            'ارشفة الفاتورة',
            'طباعةالفاتورة',
            'اضافة مرفق',
            'حذف المرفق',
            'اضافة مستخدم',
            'تعديل مستخدم',
            'حذف مستخدم',
            'عرض صلاحية',
            'اضافة صلاحية',
            'تعديل صلاحية',
            'حذف صلاحية',
            'اضافة منتج',
            'تعديل منتج',
            'حذف منتج',
            'اضافة قسم',
            'تعديل قسم',
            'حذف قسم',
            'الاشعارات',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        
    }
}