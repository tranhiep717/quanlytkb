<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\RegistrationWave;
use Illuminate\Support\Facades\Auth;

class StudentNotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $announcements = Announcement::orderBy('published_at', 'desc')->paginate(15);
        $waves = RegistrationWave::orderBy('starts_at', 'desc')->take(10)->get();
        return view('student.notifications.index', compact('announcements', 'waves'));
    }
}
