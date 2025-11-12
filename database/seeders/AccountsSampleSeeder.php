<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Faculty;

class AccountsSampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure a default faculty for mapping users
        $faculty = Faculty::first() ?? Faculty::create([
            'code' => 'CNTT',
            'name' => 'Khoa Công nghệ Thông tin',
            'is_active' => true,
        ]);

        // Super Admin
        User::updateOrCreate(
            ['email' => 'admin@dangkytinchi.edu.vn'],
            [
                'name' => 'Quản trị hệ thống',
                'password' => Hash::make('Admin@2025!'),
                'role' => 'super_admin',
                'is_locked' => false,
            ]
        );

        // Lecturer
        User::updateOrCreate(
            ['email' => 'giangvien1@dangkytinchi.edu.vn'],
            [
                'name' => 'Giảng viên Thử nghiệm',
                'code' => 'GVTEST01',
                'password' => Hash::make('GvTest@2025!'),
                'role' => 'lecturer',
                'faculty_id' => $faculty->id,
                'is_locked' => false,
            ]
        );

        // Student (align with SVTEST01 sample)
        User::updateOrCreate(
            ['email' => 'sv.test@university.edu.vn'],
            [
                'name' => 'Sinh viên Mẫu',
                'code' => 'SVTEST01',
                'password' => Hash::make('SvTest@2025!'),
                'role' => 'student',
                'faculty_id' => $faculty->id,
                'class_cohort' => 'K17',
                'is_locked' => false,
            ]
        );

        $this->command?->info('✅ Tạo tài khoản mẫu: Admin, Giảng viên, Sinh viên.');
        $this->command?->line('- Admin: admin@dangkytinchi.edu.vn / Admin@2025!');
        $this->command?->line('- Giảng viên: giangvien1@dangkytinchi.edu.vn (GVTEST01) / GvTest@2025!');
        $this->command?->line('- Sinh viên: sv.test@university.edu.vn (SVTEST01) / SvTest@2025!');
    }
}
