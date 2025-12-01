<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء مستخدم admin إذا لم يكن موجوداً
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'المدير',
                'email' => 'admin@admin.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@example.com');
        $this->command->info('Password: password');
    }
}
