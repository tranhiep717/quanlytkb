<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use App\Models\User;

class StudentOtpTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Known sample student seeded earlier
        $studentEmail = 'sv.test@university.edu.vn';
        $studentCode = 'SVTEST01';
        $otp = '123456';

        $user = User::where('email', $studentEmail)->orWhere('code', $studentCode)->first();

        if (!$user) {
            $this->command?->warn('Sample student not found. Skipping OTP seed.');
            return;
        }

        // Clear any existing OTP records for this user
        DB::table('password_otps')->where('user_id', $user->id)->delete();

        // Insert fresh OTP valid for 10 minutes from now (validation checks created_at <= 10 minutes)
        DB::table('password_otps')->insert([
            'user_id'    => $user->id,
            'code'       => $user->code,
            'email'      => $user->email,
            'otp'        => Hash::make($otp),
            'created_at' => now(),
        ]);

        $this->command?->info("Seeded test OTP '$otp' for student {$user->code} ({$user->email}). Valid for 10 minutes from now.");
    }
}
