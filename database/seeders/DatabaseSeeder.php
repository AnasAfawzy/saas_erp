<?php


namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء الصلاحيات
        $permissions = [
            ['name' => 'view_users', 'display_name' => 'عرض المستخدمين', 'description' => 'يمكن عرض قائمة المستخدمين'],
            ['name' => 'create_users', 'display_name' => 'إنشاء مستخدمين', 'description' => 'يمكن إنشاء مستخدمين جدد'],
            ['name' => 'edit_users', 'display_name' => 'تعديل المستخدمين', 'description' => 'يمكن تعديل بيانات المستخدمين'],
            ['name' => 'delete_users', 'display_name' => 'حذف المستخدمين', 'description' => 'يمكن حذف المستخدمين'],
            ['name' => 'manage_roles', 'display_name' => 'إدارة الأدوار', 'description' => 'يمكن إدارة الأدوار والصلاحيات'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // إنشاء الأدوار
        $adminRole = Role::create([
            'name' => 'admin',
            'display_name' => 'مدير النظام',
            'description' => 'صلاحيات كاملة للنظام'
        ]);

        $userRole = Role::create([
            'name' => 'user',
            'display_name' => 'مستخدم',
            'description' => 'مستخدم عادي'
        ]);

        // ربط الصلاحيات بالأدوار
        $adminRole->permissions()->attach(Permission::all());
        $userRole->permissions()->attach(Permission::where('name', 'view_users')->first());

        // إنشاء مستخدم تجريبي
        $adminUser = User::factory()->create([
            'name' => 'مدير النظام',
            'email' => 'admin@example.com',
            'password' => Hash::make('123456789'),
        ]);

        $regularUser = User::factory()->create([
            'name' => 'مستخدم تجريبي',
            'email' => 'user@example.com',
            'password' => Hash::make('123456789'),
        ]);

        // ربط المستخدمين بالأدوار
        $adminUser->assignRole('admin');
        $regularUser->assignRole('user');

    }
}
