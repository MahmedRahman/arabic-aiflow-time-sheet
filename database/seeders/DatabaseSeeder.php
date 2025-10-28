<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // إنشاء مدير
        User::create([
            'name' => 'المدير',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // إنشاء عميل
        $client = \App\Models\Client::create([
            'name' => 'أحمد محمد',
            'email' => 'client@example.com',
            'phone' => '01234567890',
            'company' => 'شركة التقنية',
            'address' => 'القاهرة، مصر',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'name' => 'أحمد محمد',
            'email' => 'client@example.com',
            'password' => bcrypt('password'),
            'role' => 'client',
        ]);

        // إنشاء مشروع
        $project = \App\Models\Project::create([
            'client_id' => $client->id,
            'name' => 'تطبيق ويب للتجارة الإلكترونية',
            'description' => 'تطوير موقع للتجارة الإلكترونية مع نظام دفع',
            'hourly_rate' => 150.00,
            'status' => 'active',
            'start_date' => now()->subDays(30),
        ]);

        // إنشاء سجلات وقت
        \App\Models\TimeEntry::create([
            'project_id' => $project->id,
            'user_id' => 1,
            'date' => now()->subDays(5),
            'start_time' => '09:00',
            'end_time' => '17:00',
            'hours_worked' => 8,
            'description' => 'تطوير واجهة المستخدم',
            'hourly_rate' => 150.00,
            'total_amount' => 1200.00,
            'status' => 'approved',
        ]);

        \App\Models\TimeEntry::create([
            'project_id' => $project->id,
            'user_id' => 1,
            'date' => now()->subDays(3),
            'start_time' => '10:00',
            'end_time' => '16:00',
            'hours_worked' => 6,
            'description' => 'تطوير قاعدة البيانات',
            'hourly_rate' => 150.00,
            'total_amount' => 900.00,
            'status' => 'pending',
        ]);
    }
}
