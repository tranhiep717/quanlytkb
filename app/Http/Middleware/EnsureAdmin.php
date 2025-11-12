<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * Handle an incoming request.
     *
     * Allows users with role 'super_admin' or 'faculty_admin'.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('admin.login');
        }

        $role = $user->role ?? null;
        // Cho phép cả 'admin' tiêu chuẩn ngoài super_admin/faculty_admin
        if (!in_array($role, ['super_admin', 'faculty_admin', 'admin'])) {
            // If not admin, redirect to student dashboard
            return redirect()->route('student.dashboard')->with('error', 'Bạn không có quyền truy cập khu vực Quản trị.');
        }

        return $next($request);
    }
}
