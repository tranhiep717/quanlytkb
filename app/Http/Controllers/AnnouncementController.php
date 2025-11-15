<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Faculty;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::query();

        if ($search = $request->query('q')) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($status = $request->query('status')) {
            if ($status === 'published') {
                $query->whereNotNull('published_at');
            } elseif ($status === 'draft') {
                $query->whereNull('published_at');
            }
        }

        $announcements = $query->orderByDesc('published_at')->orderByDesc('created_at')->paginate(20);

        return view('admin.announcements.index', [
            'announcements' => $announcements,
            'filters' => $request->query(),
        ]);
    }

    public function create()
    {
        $faculties = Faculty::orderBy('name')->get();
        return view('admin.announcements.create', compact('faculties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'audience_type' => 'required|in:all,lecturers,students,faculty_lecturers,faculty_students',
            'faculty_id' => 'nullable|exists:faculties,id',
            'publish' => 'required|in:draft,publish',
        ]);

        $audience = $this->buildAudience($validated['audience_type'], $validated['faculty_id'] ?? null);

        Announcement::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'audience' => $audience,
            'published_at' => $validated['publish'] === 'publish' ? now() : null,
        ]);

        return redirect()->route('announcements.index')->with('success', 'Đã tạo thông báo.');
    }

    public function edit(Announcement $announcement)
    {
        $faculties = Faculty::orderBy('name')->get();
        // Derive audience_type + faculty_id from stored audience JSON
        [$audienceType, $facultyId] = $this->deriveAudienceType($announcement->audience);
        return view('admin.announcements.edit', compact('announcement', 'faculties', 'audienceType', 'facultyId'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'audience_type' => 'required|in:all,lecturers,students,faculty_lecturers,faculty_students',
            'faculty_id' => 'nullable|exists:faculties,id',
            'publish' => 'required|in:draft,publish',
        ]);

        $audience = $this->buildAudience($validated['audience_type'], $validated['faculty_id'] ?? null);

        $announcement->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'audience' => $audience,
            'published_at' => $validated['publish'] === 'publish' ? ($announcement->published_at ?? now()) : null,
        ]);

        return redirect()->route('announcements.index')->with('success', 'Đã cập nhật thông báo.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->route('announcements.index')->with('success', 'Đã xóa thông báo.');
    }

    private function buildAudience(string $type, $facultyId)
    {
        switch ($type) {
            case 'all':
                return ['roles' => ['all']];
            case 'lecturers':
                return ['roles' => ['lecturers']];
            case 'students':
                return ['roles' => ['students']];
            case 'faculty_lecturers':
                return ['roles' => ['lecturers'], 'faculties' => [$facultyId ? (int)$facultyId : null]];
            case 'faculty_students':
                return ['roles' => ['students'], 'faculties' => [$facultyId ? (int)$facultyId : null]];
            default:
                return ['roles' => ['all']];
        }
    }

    private function deriveAudienceType($audience)
    {
        $aud = is_array($audience) ? $audience : [];
        $roles = $aud['roles'] ?? [];
        $facs = $aud['faculties'] ?? [];
        $facId = is_array($facs) && count($facs) ? (int)$facs[0] : null;

        if (in_array('all', $roles)) return ['all', null];
        if (in_array('lecturers', $roles) && $facId) return ['faculty_lecturers', $facId];
        if (in_array('students', $roles) && $facId) return ['faculty_students', $facId];
        if (in_array('lecturers', $roles)) return ['lecturers', null];
        if (in_array('students', $roles)) return ['students', null];
        return ['all', null];
    }
}
