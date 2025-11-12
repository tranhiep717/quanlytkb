<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Handle an incoming request.
     * Usage: ->middleware('role:student') or 'role:lecturer,student'
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login');
        }
        $role = $user->role ?? null;
        if (!$role || (!empty($roles) && !in_array($role, $roles))) {
            // Chuyển hướng về trang welcome thay vì route 'home' (tránh lỗi RouteNotFoundException)
            return redirect()->route('welcome')->with('error', 'Bạn không có quyền truy cập trang này.');
        }
        return $next($request);
    }
}
