<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentProfileController extends Controller
{
    public function show()
    {
        return view('student.profile');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|numeric',
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:Nam,Nữ,Khác',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:png,jpg,jpeg,gif|max:2048',
            'current_password' => [
                'nullable',
                'required_with:password',
                function ($attribute, $value, $fail) use ($user) {
                    if ($value && !Hash::check($value, $user->password)) {
                        $fail('🔒 Mật khẩu hiện tại bạn nhập không chính xác. Vui lòng kiểm tra và thử lại.');
                    }
                },
            ],
            'password' => 'nullable|min:8|confirmed',
        ], [
            'name.required' => '⚠️ Vui lòng nhập họ và tên của bạn.',
            'name.string' => '⚠️ Họ và tên phải là chuỗi ký tự hợp lệ.',
            'name.max' => '⚠️ Họ và tên không được vượt quá 255 ký tự.',
            'email.required' => '⚠️ Vui lòng nhập địa chỉ email.',
            'email.email' => '⚠️ Địa chỉ email không đúng định dạng. Ví dụ: example@gmail.com',
            'email.unique' => '⚠️ Email này đã được sử dụng bởi tài khoản khác. Vui lòng sử dụng email khác.',
            'phone.numeric' => '⚠️ Số điện thoại chỉ được chứa các chữ số từ 0-9.',
            'dob.date' => '⚠️ Ngày sinh không đúng định dạng. Vui lòng chọn lại ngày sinh hợp lệ.',
            'gender.in' => '⚠️ Giới tính phải là Nam, Nữ hoặc Khác.',
            'avatar.image' => '⚠️ File tải lên phải là hình ảnh (không phải video hay tài liệu).',
            'avatar.mimes' => '⚠️ Ảnh đại diện chỉ chấp nhận các định dạng: PNG, JPG, JPEG, GIF.',
            'avatar.max' => '⚠️ Kích thước ảnh đại diện không được vượt quá 2MB. Vui lòng chọn ảnh nhỏ hơn.',
            'current_password.required_with' => '🔒 Bạn phải nhập mật khẩu hiện tại để xác nhận thay đổi mật khẩu mới.',
            'password.min' => '🔒 Mật khẩu mới phải có ít nhất 8 ký tự để đảm bảo an toàn.',
            'password.confirmed' => '🔒 Mật khẩu xác nhận không khớp với mật khẩu mới. Vui lòng nhập lại.',
        ]);

        try {
            // Handle avatar upload if provided
            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                $filename = time() . '_' . $user->id . '.' . $avatar->getClientOriginalExtension();
                $path = $avatar->storeAs('public/avatars', $filename);
                $validated['avatar_url'] = asset('storage/avatars/' . $filename);
            }

            // Remove avatar and password fields from validated data as we handle them separately
            unset($validated['avatar']);
            unset($validated['current_password']);
            unset($validated['password']);
            unset($validated['password_confirmation']);

            $user->update($validated);

            // Handle password change if provided
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
                $user->save();
            }

            return back()->with('status', '✅ Cập nhật thông tin cá nhân thành công!' . ($request->filled('password') ? ' Mật khẩu của bạn cũng đã được thay đổi.' : ''));
        } catch (\Exception $e) {
            return back()->with('error', '❌ Đã xảy ra lỗi hệ thống, không thể cập nhật thông tin. Vui lòng thử lại sau hoặc liên hệ quản trị viên nếu lỗi vẫn tiếp diễn.');
        }
    }
}
