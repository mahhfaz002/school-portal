<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModuleSmokeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    private function admin(): User
    {
        return User::where('role', 'proprietor')->firstOrFail();
    }

    /** @dataProvider pageProvider */
    public function test_page_renders(string $path): void
    {
        $this->actingAs($this->admin())->get($path)->assertOk();
    }

    public static function pageProvider(): array
    {
        return [
            'students'          => ['/students'],
            'students.create'   => ['/students/create'],
            'subjects'          => ['/subjects'],
            'attendance'        => ['/attendance'],
            'attendance report' => ['/attendance/report'],
            'inventory'         => ['/inventory'],
            'inventory.create'  => ['/inventory/create'],
            'admissions'        => ['/admin/admissions'],
            'promotion'         => ['/promotion'],
            'scores.entry'      => ['/scores/entry'],
            'announcements'     => ['/announcements'],
            'settings'          => ['/settings'],
            'staff'             => ['/staff'],
            'timetable'         => ['/timetable'],
            'library'           => ['/library'],
            'exams'             => ['/exams'],
            'transport'         => ['/transport'],
            'hr'                => ['/hr'],
            'alumni'            => ['/alumni'],
        ];
    }

    public function test_announcement_lifecycle(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post('/announcements', [
            'title' => 'Resumption',
            'body' => 'School resumes Monday.',
            'audience' => 'all',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('announcements', ['title' => 'Resumption']);
    }
}
