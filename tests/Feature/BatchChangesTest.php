<?php

namespace Tests\Feature;

use App\Models\Payslip;
use App\Models\SchoolClass;
use App\Models\Setting;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BatchChangesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    private function as(string $role): User
    {
        return User::where('role', $role)->firstOrFail();
    }

    // --- Item 1: only principal + ICT manage subjects ---
    public function test_teacher_cannot_manage_subjects(): void
    {
        $this->actingAs($this->as('teacher'))->post('/subjects', ['name' => 'Hacking', 'section' => 'Primary'])->assertForbidden();
    }

    public function test_principal_and_ict_can_add_subjects(): void
    {
        $this->actingAs($this->as('principal'))->post('/subjects', ['name' => 'Civic Ed', 'section' => 'Junior Secondary'])->assertRedirect();
        $this->actingAs($this->as('ict'))->post('/subjects', ['name' => 'ICT Studies', 'section' => 'Senior Secondary'])->assertRedirect();
        $this->assertDatabaseHas('subjects', ['name' => 'Civic Ed', 'section' => 'Junior Secondary']);
    }

    // --- Item 5: CA entry scoped to assigned subjects ---
    public function test_teacher_cannot_post_scores_for_unassigned_subject(): void
    {
        $teacher = $this->as('teacher');
        $subject = Subject::create(['name' => 'Unassigned Subj', 'section' => 'Primary']);

        $this->actingAs($teacher)->post('/scores/store', [
            'subject_id' => $subject->id,
            'scores' => [1 => ['ca' => 10, 'exam' => 20]],
        ])->assertForbidden();
    }

    // --- Item 6: principal term/session control ---
    public function test_principal_sets_active_term(): void
    {
        $this->actingAs($this->as('principal'))->post('/term', [
            'current_session' => '2026/2027',
            'current_term' => 'Second Term',
            'term_start' => '2027-01-10',
            'term_end' => '2027-04-10',
        ])->assertRedirect();

        $this->assertSame('Second Term', Setting::get('current_term'));
        $this->assertSame('2026/2027', Setting::get('current_session'));
    }

    public function test_non_principal_cannot_set_term(): void
    {
        $this->actingAs($this->as('ict'))->post('/term', [
            'current_session' => '2026/2027', 'current_term' => 'Second Term',
            'term_start' => '2027-01-10', 'term_end' => '2027-04-10',
        ])->assertForbidden();
    }

    public function test_principal_can_clear_assignments(): void
    {
        $teacher = $this->as('teacher');
        $class = SchoolClass::first();
        $subject = Subject::first();
        $teacher->classes()->sync([$class->id]);
        $teacher->subjects()->sync([$subject->id]);

        $this->actingAs($this->as('principal'))->post('/term/clear-assignments')->assertRedirect();

        $this->assertCount(0, $teacher->fresh()->classes);
        $this->assertCount(0, $teacher->fresh()->subjects);
    }

    // --- Item 8: notifications page ---
    public function test_notifications_page_loads_for_each_role(): void
    {
        foreach (['principal', 'accountant', 'ict', 'teacher', 'student'] as $role) {
            $user = User::where('role', $role)->first();
            if ($user) {
                $this->actingAs($user)->get('/notifications')->assertOk();
            }
        }
    }

    // --- Item 4: payslip view is for staff with payroll access ---
    public function test_payslip_view_accessible_to_bursar(): void
    {
        $teacher = $this->as('teacher');
        $this->actingAs($this->as('accountant'))->post('/payroll/'.$teacher->id, [
            'month' => '2026-06', 'basic_salary' => 50000, 'allowances' => 0, 'tax' => 0,
        ])->assertRedirect();
        $slip = Payslip::where('user_id', $teacher->id)->firstOrFail();

        $this->actingAs($this->as('accountant'))->get("/payroll/{$slip->id}/slip")->assertOk();
        // A teacher has no payroll route access at all.
        $this->actingAs($teacher)->get("/payroll/{$slip->id}/slip")->assertForbidden();
    }
}
