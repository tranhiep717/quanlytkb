<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password as PasswordRule;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Hiển thị trang Welcome/Landing
     */
    public function welcome()
    {
        // Nếu đã đăng nhập, chuyển về dashboard tương ứng
        if (Auth::check()) {
            return $this->redirectToDashboard(Auth::user());
        }

        return view('welcome-landing');
    }

    /**
     * UC1.1 - Bước 1: Hiển thị giao diện đăng nhập chung (cho cả Admin và Student)
     */
    public function showLoginForm()
    {
        // Kiểm tra nếu người dùng đã đăng nhập
        if (Auth::check()) {
            return $this->redirectToDashboard(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * Giao diện đăng nhập riêng cho Admin
     */
    public function showAdminLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    /**
     * UC1.1 - Xử lý đăng nhập với đầy đủ validation và bảo mật
     * Backend tự động nhận diện role và chuyển hướng phù hợp
     */
    public function login(Request $request)
    {
        // Bước 5: Kiểm tra Hợp lệ Dữ liệu Đầu vào (Validation)
        $request->validate([
            'username' => 'required|string|max:100',
            'password' => 'required|string|min:6|max:50',
        ], [
            'username.required' => 'Vui lòng nhập tài khoản.',
            'username.max' => 'Tài khoản không được vượt quá 100 ký tự.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.max' => 'Mật khẩu không được vượt quá 50 ký tự.',
        ]);

        // Kiểm tra Rate Limiting (Bảo vệ chống brute force)
        $throttleKey = strtolower($request->input('username')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->with('error', "Quá nhiều lần đăng nhập sai. Vui lòng thử lại sau {$seconds} giây.")
                ->withInput($request->only('username'));
        }

        // Tìm user theo username (có thể là email, code MSSV, hoặc name)
        $username = $request->username;
        $user = User::where('email', $username)
            ->orWhere('code', $username)
            ->first();

        if (!$user) {
            // Luồng 6a: Sai thông báo đăng nhập
            RateLimiter::hit($throttleKey, 300); // 5 phút
            return back()->with('error', 'Tài khoản không tồn tại trong hệ thống.')
                ->withInput($request->only('username'));
        }

        // Luồng 6b: Kiểm tra tài khoản có bị khóa không
        if (isset($user->is_locked) && $user->is_locked) {
            return back()->with('error', 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ phòng đào tạo.')
                ->withInput($request->only('username'));
        }

        // Thử đăng nhập với email và password
        $credentials = [
            'email' => $user->email,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Clear rate limiter khi đăng nhập thành công
            RateLimiter::clear($throttleKey);

            // Bước 7: Thiết lập phiên làm việc
            $request->session()->regenerate();

            // Log hoạt động đăng nhập
            Log::info('User logged in', [
                'user_id' => Auth::id(),
                'email' => Auth::user()->email,
                'role' => Auth::user()->role,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Chuyển hướng dựa trên role (Backend tự động nhận diện)
            return $this->redirectToDashboard($user);
        }

        // Luồng 6a: Mật khẩu không đúng
        RateLimiter::hit($throttleKey, 300);

        return back()->with('error', 'Mật khẩu không chính xác.')
            ->withInput($request->only('username'));
    }

    /**
     * Chuyển hướng người dùng dựa trên role
     * Backend tự động nhận diện và redirect
     */
    private function redirectToDashboard(User $user)
    {
        // Lưu role vào session
        session(['user_role' => $user->role]);

        switch ($user->role) {
            case 'admin':
            case 'super_admin':
            case 'faculty_admin':
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Chào mừng Admin ' . $user->name . '!');

            case 'student':
                return redirect()->route('student.dashboard')
                    ->with('success', 'Chào mừng ' . $user->name . '!');

            case 'lecturer':
                // Nếu có role giảng viên
                return redirect()->route('lecturer.dashboard')
                    ->with('success', 'Chào mừng giảng viên ' . $user->name . '!');

            default:
                // Role không xác định
                Auth::logout();
                return redirect()->route('login')
                    ->with('error', 'Vai trò người dùng không hợp lệ. Vui lòng liên hệ quản trị viên.');
        }
    }

    /**
     * Xử lý đăng nhập Admin: Chỉ cho phép vai trò super_admin hoặc faculty_admin
     */
    public function adminLogin(Request $request)
    {
        // Tái sử dụng logic validate giống login()
        try {
            $request->validate([
                'email' => [
                    'required',
                    'string',
                    'max:100',
                    function ($attribute, $value, $fail) {
                        if (str_contains($value, '@')) {
                            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                $fail('Email không đúng định dạng.');
                            }
                        }
                    },
                ],
                'password' => 'required|string|min:6|max:50',
            ]);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput($request->only('email'));
        }

        $throttleKey = 'admin:' . strtolower($request->input('email')) . '|' . $request->ip();
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($throttleKey);
            return back()->with('error', "Quá nhiều lần đăng nhập sai. Vui lòng thử lại sau {$seconds} giây.")
                ->withInput($request->only('email'));
        }

        $loginField = $this->getLoginField($request->email);
        $credentials = [
            $loginField => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            if (!in_array($user->role ?? null, ['super_admin', 'faculty_admin'])) {
                Auth::logout();
                return back()->with('error', 'Tài khoản không có quyền truy cập trang Quản trị.');
            }
            return redirect()->intended(route('admin.dashboard'));
        }

        \Illuminate\Support\Facades\RateLimiter::hit($throttleKey, 300);
        return back()->with('error', 'Email hoặc Mật khẩu không đúng.')
            ->withInput($request->only('email'));
    }

    /**
     * Xác định trường đăng nhập (email hoặc username)
     */
    private function getLoginField($input)
    {
        // Cho phép đăng nhập bằng Email hoặc MSSV (code)
        return filter_var($input, FILTER_VALIDATE_EMAIL) ? 'email' : 'code';
    }

    /**
     * Hiển thị trang đăng ký
     */
    public function showRegisterForm()
    {
        return view('register');
    }

    /**
     * UC1.3 - Hiển thị form đổi mật khẩu (đã đăng nhập)
     */
    public function showChangePasswordForm()
    {
        $user = auth()->user();
        if ($user && in_array($user->role, ['super_admin', 'faculty_admin', 'admin'])) {
            return view('admin.change-password');
        }
        if ($user && $user->role === 'student') {
            return view('student.profile.change-password');
        }
        if ($user && $user->role === 'lecturer') {
            return view('lecturer.profile.change-password');
        }
        return view('change-password');
    }

    /**
     * UC1.3 - Xử lý đổi mật khẩu
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()->symbols()->uncompromised()],
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.confirmed' => 'Mật khẩu xác nhận không trùng khớp.',
            'password.letters' => 'Mật khẩu phải chứa ít nhất một chữ cái.',
            'password.mixed' => 'Mật khẩu phải có ít nhất một chữ hoa và một chữ thường.',
            'password.numbers' => 'Mật khẩu phải chứa ít nhất một chữ số.',
            'password.symbols' => 'Mật khẩu phải chứa ít nhất một ký tự đặc biệt.',
            'password.uncompromised' => 'Mật khẩu đã xuất hiện trong rò rỉ dữ liệu. Vui lòng chọn mật khẩu khác.',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Mật khẩu hiện tại không đúng.');
        }

        if (Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Mật khẩu mới không được trùng với mật khẩu hiện tại.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Invalidate other sessions
        $currentId = $request->session()->getId();
        DB::table('sessions')
            ->where('user_id', $user->id)
            ->where('id', '!=', $currentId)
            ->delete();

        // Optionally rotate remember token
        $user->setRememberToken(Str::random(60));
        $user->save();

        // Send notification email
        try {
            Mail::send('emails.password-changed', ['user' => $user], function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Mật khẩu của bạn đã được thay đổi');
            });
        } catch (\Exception $e) {
            Log::warning('Failed to send password changed email', ['email' => $user->email, 'error' => $e->getMessage()]);
        }

        // Regenerate session for safety
        $request->session()->regenerate();

        return back()->with('status', 'Đổi mật khẩu thành công. Bạn đã được đăng xuất khỏi các thiết bị khác.');
    }
    /**
     * Xử lý đăng ký người dùng
     */
    public function register(Request $request)
    {
        // Rate limit registration to prevent abuse
        $regKey = 'register:' . $request->ip();
        if (RateLimiter::tooManyAttempts($regKey, 10)) {
            return back()->with('error', 'Bạn đã thực hiện quá nhiều yêu cầu đăng ký. Vui lòng thử lại sau ít phút.')->withInput();
        }
        RateLimiter::hit($regKey, 300); // 5 phút

        // Validate with strong password policy
        $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:255',
            'password' => ['required', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()->symbols()->uncompromised()],
        ], [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.confirmed' => 'Mật khẩu xác nhận không trùng khớp.',
            'password.letters' => 'Mật khẩu phải chứa ít nhất một chữ cái.',
            'password.mixed' => 'Mật khẩu phải có ít nhất một chữ hoa và một chữ thường.',
            'password.numbers' => 'Mật khẩu phải chứa ít nhất một chữ số.',
            'password.symbols' => 'Mật khẩu phải chứa ít nhất một ký tự đặc biệt.',
            'password.uncompromised' => 'Mật khẩu đã xuất hiện trong rò rỉ dữ liệu. Vui lòng chọn mật khẩu khác.',
        ]);

        // Kiểm tra email đã tồn tại
        if (User::where('email', $request->email)->exists()) {
            return back()->with('error', 'Email này đã được sử dụng. Vui lòng sử dụng email khác.')->withInput();
        }

        // Tạo user mới
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        // Đăng nhập tự động
        Auth::login($user);

        Log::info('New user registered', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
        ]);

        return redirect()->intended('/dashboard')->with('success', 'Đăng ký thành công. Chào mừng ' . $user->name . '!');
    }

    /**
     * Đăng xuất
     */
    public function logout(Request $request)
    {
        $userId = Auth::id();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Log hoạt động đăng xuất
        Log::info('User logged out', [
            'user_id' => $userId,
            'ip' => $request->ip(),
        ]);

        return redirect('/')->with('success', 'Đăng xuất thành công!');
    }

    /**
     * UC1.2 - Bước 1: Hiển thị form quên mật khẩu
     */
    public function showForgotPasswordForm()
    {
        return view('forgot-password');
    }

    /**
     * UC1.2 - Bước 5: Xử lý yêu cầu gửi link reset mật khẩu
     * Luồng 5a: Thông báo lỗi nếu thông tin không đúng hoặc không trùng khớp
     * Luồng 5b: Thông báo thành công nếu gửi được liên kết
     */
    public function sendResetLink(Request $request)
    {
        // UC1.2 - Bước 3, 5: Kiểm tra định dạng email
        $request->validate([
            'email' => 'required|email|max:255',
        ], [
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
        ]);

        // Rate limit the reset requests
        $throttleKey = 'reset:' . Str::lower($request->input('email')) . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 10)) {
            return back()->with('error', 'Bạn đã yêu cầu quá nhiều lần. Vui lòng thử lại sau vài phút.')
                ->withInput();
        }
        RateLimiter::hit($throttleKey, 60); // 1 phút cửa sổ

        // UC1.2 - Bước 5: Kiểm tra tồn tại tài khoản ứng với email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Luồng 5a: Thông báo lỗi nếu không tìm thấy tài khoản
            return back()->with('error', 'Không tìm thấy tài khoản với email này.')
                ->withInput();
        }

        // Tạo token reset password (hashed để bảo mật)
        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        // UC1.2 - Hậu điều kiện: Gửi liên kết thiết lập lại mật khẩu (tồn tại 60 phút)
        $resetLink = url(route('password.reset', [
            'token' => $token,
            'email' => $request->email
        ], false));

        try {
            Mail::send('emails.reset-password', ['resetLink' => $resetLink, 'user' => $user], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Thiết Lập Lại Mật Khẩu - Hệ Thống Đăng Ký Tín Chỉ');
            });
            Log::info('Password reset link sent', ['email' => $request->email, 'ip' => $request->ip()]);

            // Luồng 5b: Thông báo thành công nếu gửi được liên kết
            return back()->with('status', 'Liên kết thiết lập lại mật khẩu đã được gửi đến email của bạn. Vui lòng kiểm tra hộp thư (bao gồm cả thư mục spam).');
        } catch (\Exception $e) {
            Log::error('Failed to send password reset email', ['email' => $request->email, 'error' => $e->getMessage()]);

            // Luồng 5a: Thông báo lỗi nếu không gửi được email
            return back()->with('error', 'Không thể gửi email. Vui lòng thử lại sau hoặc liên hệ quản trị viên.')
                ->withInput();
        }
    }

    /**
     * UC1.2: Hiển thị form reset password từ link email
     */
    public function showResetPasswordForm(Request $request, $token)
    {
        return view('reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * UC1.2: Xử lý reset password
     */
    public function resetPassword(Request $request)
    {
        // Validation with strong policy
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()->symbols()->uncompromised()],
        ], [
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.confirmed' => 'Mật khẩu xác nhận không trùng khớp.',
            'password.letters' => 'Mật khẩu phải chứa ít nhất một chữ cái.',
            'password.mixed' => 'Mật khẩu phải có ít nhất một chữ hoa và một chữ thường.',
            'password.numbers' => 'Mật khẩu phải chứa ít nhất một chữ số.',
            'password.symbols' => 'Mật khẩu phải chứa ít nhất một ký tự đặc biệt.',
            'password.uncompromised' => 'Mật khẩu đã xuất hiện trong rò rỉ dữ liệu. Vui lòng chọn mật khẩu khác.',
        ]);

        // Kiểm tra token có hợp lệ không (trong vòng 60 phút)
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset) {
            return back()->with('error', 'Liên kết thiết lập lại mật khẩu không hợp lệ.');
        }

        // Kiểm tra token có đúng không
        if (!Hash::check($request->token, $passwordReset->token)) {
            return back()->with('error', 'Liên kết thiết lập lại mật khẩu không hợp lệ.');
        }

        // Kiểm tra thời gian (60 phút = 3600 giây)
        $createdAt = \Carbon\Carbon::parse($passwordReset->created_at);
        if ($createdAt->addMinutes(60)->isPast()) {
            // Xóa token hết hạn
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->with('error', 'Liên kết thiết lập lại mật khẩu đã hết hạn. Vui lòng yêu cầu gửi lại.');
        }

        // Cập nhật mật khẩu mới
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Không tìm thấy người dùng.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Invalidate other sessions (database driver)
        $currentId = $request->session()->getId();
        DB::table('sessions')
            ->where('user_id', $user->id)
            ->where('id', '!=', $currentId)
            ->delete();

        // Xóa token sau khi sử dụng
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Gửi email thông báo đổi mật khẩu
        try {
            Mail::send('emails.password-changed', ['user' => $user], function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Mật khẩu của bạn đã được thay đổi');
            });
        } catch (\Exception $e) {
            Log::warning('Failed to send password changed email', ['email' => $user->email, 'error' => $e->getMessage()]);
        }

        // Log hoạt động
        Log::info('Password reset successful', [
            'email' => $request->email,
            'ip' => $request->ip()
        ]);

        // Chuyển về trang đăng nhập với thông báo thành công
        return redirect()->route('login')->with('status', 'Mật khẩu đã được thiết lập lại thành công. Vui lòng đăng nhập với mật khẩu mới.');
    }

    /**
     * UC1.5 (Student) - Hiển thị form quên mật khẩu bằng MSSV + Email (OTP)
     */
    public function showStudentForgotForm()
    {
        return view('student.auth.forgot');
    }

    /**
     * UC1.5 - Gửi OTP về email nếu MSSV và email khớp (SECURE - không tiết lộ thông tin)
     */
    public function sendStudentOtp(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'email' => 'required|email|max:255',
        ], [
            'code.required' => 'Vui lòng nhập MSSV.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
        ]);

        // Rate limit
        $key = 'otp:' . strtolower($request->email) . '|' . $request->ip();
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($key, 10)) {
            // Vẫn chuyển hướng để không lộ thông tin
            return redirect()->route('student.verifyOtp.form')
                ->with('info', 'Yêu cầu đã được gửi. Nếu MSSV và Email của bạn tồn tại trong hệ thống, một mã OTP đã được gửi đến email của bạn. Mã sẽ hết hạn sau 10 phút.');
        }
        \Illuminate\Support\Facades\RateLimiter::hit($key, 60);

        // SILENT CHECK: Kiểm tra thầm lặng, không báo lỗi
        $user = User::where('code', $request->code)
            ->where('email', $request->email)
            ->where('role', 'student')
            ->first();

        // Chỉ gửi OTP nếu user hợp lệ, nhưng LUÔN trả về response giống nhau
        if ($user) {
            $otp = random_int(100000, 999999);

            DB::table('password_otps')->updateOrInsert(
                ['user_id' => $user->id],
                [
                    'user_id' => $user->id,
                    'code' => $user->code,
                    'email' => $user->email,
                    'otp' => Hash::make((string)$otp),
                    'created_at' => now(),
                ]
            );

            try {
                Mail::send('emails.otp-reset', ['user' => $user, 'otp' => $otp], function ($message) use ($user) {
                    $message->to($user->email);
                    $message->subject('Mã OTP đặt lại mật khẩu');
                });

                Log::info('OTP sent successfully', ['user_id' => $user->id, 'email' => $user->email]);
            } catch (\Exception $e) {
                Log::error('Send OTP failed', ['email' => $user->email, 'error' => $e->getMessage()]);
                // Không trả về lỗi để tránh lộ thông tin
            }
        } else {
            // User không tồn tại hoặc không khớp - KHÔNG làm gì, KHÔNG gửi email, KHÔNG báo lỗi
            Log::info('OTP request for non-existent user', ['code' => $request->code, 'email' => $request->email]);
        }

        // LUÔN LUÔN trả về cùng một thông báo và chuyển hướng, bất kể user có tồn tại hay không
        return redirect()->route('student.verifyOtp.form')
            ->with('info', 'Yêu cầu đã được gửi. Nếu MSSV và Email của bạn tồn tại trong hệ thống, một mã OTP đã được gửi đến email của bạn. Mã sẽ hết hạn sau 10 phút.');
    }

    /**
     * UC1.5 - Hiển thị form nhập OTP và đặt mật khẩu mới
     */
    public function showVerifyOtpForm()
    {
        return view('student.auth.verify-otp');
    }

    /**
     * UC1.5 - Xác thực OTP và đặt mật khẩu mới
     */
    public function verifyOtpAndReset(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'email' => 'required|email',
            'otp' => 'required|digits:6',
            'password' => ['required', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()->symbols()->uncompromised()],
        ], [
            'code.required' => 'Vui lòng nhập MSSV.',
            'otp.required' => 'Vui lòng nhập mã OTP.',
            'otp.digits' => 'Mã OTP gồm 6 chữ số.',
            'password.confirmed' => 'Mật khẩu xác nhận không trùng khớp.',
            'password.letters' => 'Mật khẩu phải chứa ít nhất một chữ cái.',
            'password.mixed' => 'Mật khẩu phải có ít nhất một chữ hoa và một chữ thường.',
            'password.numbers' => 'Mật khẩu phải chứa ít nhất một chữ số.',
            'password.symbols' => 'Mật khẩu phải chứa ít nhất một ký tự đặc biệt.',
            'password.uncompromised' => 'Mật khẩu đã xuất hiện trong rò rỉ dữ liệu. Vui lòng chọn mật khẩu khác.',
        ]);

        $user = User::where('code', $request->code)->where('email', $request->email)->first();
        if (!$user) {
            return back()->with('error', 'Thông tin không khớp.')->withInput();
        }

        $row = DB::table('password_otps')->where('user_id', $user->id)->first();
        if (!$row) {
            return back()->with('error', 'OTP không hợp lệ hoặc đã hết hạn.');
        }
        $createdAt = \Carbon\Carbon::parse($row->created_at);
        if ($createdAt->addMinutes(10)->isPast()) {
            DB::table('password_otps')->where('user_id', $user->id)->delete();
            return back()->with('error', 'OTP đã hết hạn. Vui lòng yêu cầu lại.');
        }
        if (!Hash::check($request->otp, $row->otp)) {
            return back()->with('error', 'Mã OTP không đúng.');
        }

        $user->password = Hash::make($request->password);
        $user->save();
        DB::table('password_otps')->where('user_id', $user->id)->delete();

        return redirect()->route('login')->with('status', 'Đặt lại mật khẩu thành công. Vui lòng đăng nhập.');
    }
}
