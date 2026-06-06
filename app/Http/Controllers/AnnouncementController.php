<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Roles allowed to create/delete announcements.
     */
    private array $managers = ['proprietor', 'principal', 'admin', 'ict'];

    public function index()
    {
        $role = auth()->user()->role ?? 'student';
        $announcements = Announcement::visibleTo($role)
            ->with('author')
            ->latest()
            ->paginate(15);

        $canManage = in_array($role, $this->managers);

        return view('announcements.index', compact('announcements', 'canManage'));
    }

    public function store(Request $request)
    {
        abort_unless(in_array(auth()->user()->role, $this->managers), 403);

        $data = $request->validate([
            'title'    => 'required|string|max:255',
            'body'     => 'required|string',
            'audience' => 'required|string|in:all,staff,teacher,student,accountant,principal',
        ]);
        $data['user_id'] = auth()->id();

        Announcement::create($data);
        ActivityLog::record('Posted announcement: ' . $data['title'], 'announcement.create');

        return back()->with('success', 'Announcement posted.');
    }

    public function destroy(Announcement $announcement)
    {
        abort_unless(in_array(auth()->user()->role, $this->managers), 403);
        $announcement->delete();

        return back()->with('success', 'Announcement deleted.');
    }
}
