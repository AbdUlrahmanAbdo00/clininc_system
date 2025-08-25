<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\AdminCredential;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء دور المدير إذا لم يكن موجوداً
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // إنشاء مستخدم المدير
        $adminUser = User::create([
        
            'number' => '07701234567',
            'first_name' => 'أحمد',
            'middle_name' => 'محمد',
            'last_name' => 'علي',
            'mother_name' => 'فاطمة',
            'birth_day' => '1985-05-15',
            'national_number' => '1234567890',
            'gender' => 'ذكر',
        ]);

        // إعطاء دور المدير للمستخدم
        $adminUser->assignRole($adminRole);

        // إنشاء بيانات اعتماد المدير
        AdminCredential::create([
            'user_id' => $adminUser->id,
            'username' => 'admin',
            'password' => 'admin123',
        ]);

        $this->command->info('تم إنشاء المدير بنجاح!');
        $this->command->info('اسم المستخدم: admin');
        $this->command->info('كلمة المرور: admin123');
    }
}



