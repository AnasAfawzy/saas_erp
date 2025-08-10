<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء مستخدم للاختبار
        User::updateOrCreate(
            ['email' => 'test@test.com'],
            [
                'name' => 'مستخدم تجريبي',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
            ]
        );

        echo "تم إنشاء مستخدم تجريبي: test@test.com / 123456" . PHP_EOL;
    }
}
