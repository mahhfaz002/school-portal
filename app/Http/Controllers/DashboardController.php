<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Payment;
use App\Models\Score;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * The Multi-Role Traffic Warden.
     * Every authenticated user hits /dashboard; we render the right view per role.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        return match ($user->role) {
            'proprietor'   => $this->proprietorDashboard($request),
            'principal'    => $this->principalDashboard($request),
            'admin'        => $this->proprietorDashboard($request),
            'ict'          => $this->ictDashboard($request),
            'accountant'   => $this->accountantDashboard($request),
            'teacher'      => $this->teacherDashboard($request),
            'student'      => $this->studentDashboard($request),
            'exam_officer' => $this->examOfficerDashboard(),
            default        => $this->proprietorDashboard($request),
        };
    }

    /**
     * ROLE 1: PROPRIETOR / ADMIN (Sees Everything) — renders the rich overview.
     */
    private function proprietorDashboard(Request $request)
    {
        $search = $request->query('search', '');
        $selectedClass = $request->query('class');
        $searchResults = collect();

        if ($search) {
            $searchResults = Student::where('full_name', 'LIKE', "%{$search}%")
                ->orWhere('admission_number', 'LIKE', "%{$search}%")
                ->limit(5)->get();
        }

        $studentQuery = Student::query();
        $paymentQuery = Payment::with('student');

        if ($selectedClass) {
            $studentQuery->where('class_arm', $selectedClass);
            $paymentQuery->whereHas('student', fn($q) => $q->where('class_arm', $selectedClass));
        }

        $totalRevenue = (clone $paymentQuery)->sum('amount');
        $totalDebt = (clone $studentQuery)->sum('fees_balance');
        $studentCount = (clone $studentQuery)->count();

        $topStudents = Score::select('student_id', DB::raw('AVG(ca_score + exam_score) as average_score'))
            ->groupBy('student_id')->orderByDesc('average_score')->with('student')->take(5)->get();

        $recentPayments = (clone $paymentQuery)->latest()->take(5)->get();
        $classes = Student::select('class_arm')->whereNotNull('class_arm')->distinct()->pluck('class_arm');

        return view('dashboards.proprietor', compact(
            'totalRevenue',
            'totalDebt',
            'studentCount',
            'topStudents',
            'recentPayments',
            'classes',
            'selectedClass',
            'searchResults',
            'search'
        ));
    }

    private function teacherDashboard(Request $request)
    {
        $user = auth()->user();
        if (!$user->class_assigned) {
            return view('dashboards.teacher', [
                'students' => collect(),
                'studentCount' => 0,
                'selectedClass' => 'Not Assigned',
                'attendanceTaken' => false,
            ]);
        }
        $selectedClass = $user->class_assigned;
        $students = Student::where('class_arm', $selectedClass)->get();
        $studentCount = $students->count();
        $attendanceTaken = Attendance::where('attendance_date', date('Y-m-d'))
            ->whereIn('student_id', $students->pluck('id'))
            ->exists();
        return view('dashboards.teacher', compact('students', 'studentCount', 'selectedClass', 'attendanceTaken'));
    }

    public function storeTeacher(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'surname' => 'required|string',
            'class_assigned' => 'required|string',
            'subject_assigned' => 'required|string',
        ]);

        // Build a staff email from the school's configured domain (settings-driven).
        $domain = setting('staff_email_domain', 'school.test');
        $initial = strtolower(substr($request->first_name, 0, 1));
        $surname = strtolower($request->surname);
        $email = $initial . '.' . $surname . '@' . $domain;

        // Generate a random temporary password the teacher must change on first login.
        $randomPassword = \Illuminate\Support\Str::random(8);

        \App\Models\User::create([
            'name' => $request->first_name . ' ' . $request->surname,
            'email' => $email,
            'password' => \Illuminate\Support\Facades\Hash::make($randomPassword),
            'role' => 'teacher',
            'class_assigned' => $request->class_assigned,
            'subject_assigned' => $request->subject_assigned,
            'must_change_password' => true,
        ]);

        return back()->with('success', "Teacher Account Created! Email: $email | Temp Password: $randomPassword");
    }

    private function accountantDashboard(Request $request)
    {
        $totalRevenue = Payment::sum('amount');
        $totalDebt = Student::sum('fees_balance');
        $debtors = Student::where('fees_balance', '>', 0)
            ->orderByDesc('fees_balance')
            ->take(10)
            ->get();
        $recentPayments = Payment::with('student')
            ->latest()
            ->take(5)
            ->get();
        return view('dashboards.accountant', compact('totalRevenue', 'totalDebt', 'debtors', 'recentPayments'));
    }

    /**
     * ROLE 4: STUDENT
     */
    private function studentDashboard(Request $request)
    {
        $user = auth()->user();
        $student = Student::where('email', $user->email)->first();

        if (!$student) {
            return view('dashboards.student', [
                'error' => 'No student record linked to this account.',
                'student' => null,
                'scores' => collect(),
                'payments' => collect(),
                'attendanceRate' => 0,
            ]);
        }

        $scores = Score::where('student_id', $student->id)->with('subject')->get();
        $payments = Payment::where('student_id', $student->id)->latest()->take(5)->get();
        $totalDays = Attendance::where('student_id', $student->id)->count();
        $daysPresent = Attendance::where('student_id', $student->id)->where('status', 'present')->count();
        $attendanceRate = $totalDays > 0 ? round(($daysPresent / $totalDays) * 100) : 100;

        return view('dashboards.student', compact('student', 'scores', 'payments', 'attendanceRate'));
    }

    /**
     * SUPERADMIN / PROPRIETOR SWITCHBOARD
     */
    public function switchboard()
    {
        return view('dashboards.switchboard');
    }

    private function principalDashboard(Request $request)
    {
        $staffCount = \App\Models\User::where('role', '!=', 'student')->count();
        $unassignedTeachers = \App\Models\User::where('role', 'teacher')
            ->whereNull('class_assigned')
            ->count();
        $totalStudents = Student::count();
        $attendanceToday = Attendance::where('attendance_date', date('Y-m-d'))->count();
        $staffList = \App\Models\User::where('role', '!=', 'student')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboards.principal', compact(
            'staffCount',
            'unassignedTeachers',
            'totalStudents',
            'attendanceToday',
            'staffList'
        ));
    }

    private function examOfficerDashboard()
    {
        $totalStudents = Student::count();
        $subjectsCount = \App\Models\Subject::count();
        $scoresEntered = Score::count();
        return view('dashboards.exam_officer', compact('totalStudents', 'subjectsCount', 'scoresEntered'));
    }

    private function ictDashboard(Request $request)
    {
        $totalUsers = \App\Models\User::count();
        $activeSessions = DB::table('sessions')->count();
        $latestSystemLogs = \App\Models\ActivityLog::with('user')->latest()->take(10)->get();

        return view('dashboards.ict', compact(
            'totalUsers',
            'activeSessions',
            'latestSystemLogs'
        ));
    }
}
