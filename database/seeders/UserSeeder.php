<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Faculty;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa dữ liệu cũ (nếu có)
        DB::statement('PRAGMA foreign_keys = OFF');
        DB::table('users')->truncate();
        DB::table('faculties')->truncate();
        DB::statement('PRAGMA foreign_keys = ON');

        // Tạo Khoa mẫu
        $cntt = Faculty::create(['code' => 'CNTT', 'name' => 'Khoa Công nghệ Thông tin']);
        $kinhte = Faculty::create(['code' => 'KT', 'name' => 'Khoa Kinh tế']);

        // Tạo tài khoản Quản trị viên (Admin)
        User::create([
            'name' => 'Quản Trị Viên',
            'email' => 'admin@dangkytinchi.edu.vn',
            'password' => Hash::make('admin123456'),
            'email_verified_at' => now(),
            'role' => 'super_admin',
        ]);

        // Tạo tài khoản Giảng viên 1
        User::create([
            'name' => 'Nguyễn Văn Giảng',
            'email' => 'giangvien1@dangkytinchi.edu.vn',
            'password' => Hash::make('giang123456'),
            'email_verified_at' => now(),
            'role' => 'lecturer',
            'faculty_id' => $cntt->id,
        ]);

        // Tạo tài khoản Giảng viên 2
        User::create([
            'name' => 'Trần Thị Hương',
            'email' => 'giangvien2@dangkytinchi.edu.vn',
            'password' => Hash::make('giang123456'),
            'email_verified_at' => now(),
            'role' => 'lecturer',
            'faculty_id' => $kinhte->id,
        ]);

        // Tạo tài khoản Học viên 1
        User::create([
            'name' => 'Lê Văn An',
            'email' => 'hocvien1@dangkytinchi.edu.vn',
            'password' => Hash::make('hocvien123'),
            'email_verified_at' => now(),
            'role' => 'student',
            'faculty_id' => $cntt->id,
            'class_cohort' => 'K17',
        ]);

        // Tạo tài khoản Học viên 2
        User::create([
            'name' => 'Phạm Thị Bình',
            'email' => 'hocvien2@dangkytinchi.edu.vn',
            'password' => Hash::make('hocvien123'),
            'email_verified_at' => now(),
            'role' => 'student',
            'faculty_id' => $cntt->id,
            'class_cohort' => 'K18',
        ]);

        // Tạo tài khoản Học viên 3
        User::create([
            'name' => 'Hoàng Văn Cường',
            'email' => 'hocvien3@dangkytinchi.edu.vn',
            'password' => Hash::make('hocvien123'),
            'email_verified_at' => now(),
            'role' => 'student',
            'faculty_id' => $kinhte->id,
            'class_cohort' => 'K17',
        ]);

        // Tạo tài khoản test
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'role' => 'student',
            'faculty_id' => $cntt->id,
            'class_cohort' => 'K18',
        ]);
    }
}
