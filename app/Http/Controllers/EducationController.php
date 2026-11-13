<?php

namespace App\Http\Controllers;

use App\Enums\GradeLevel;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class EducationController extends Controller
{
    /**
     * Show the curriculum tables - one table per major.
     *
     * The tables are configured in config/education.php, so editing that
     * file changes the page: no database, migration or seeder is involved.
     * The shape of the file is the contract the future admin panel will
     * write to.
     */
    public function index(): View
    {
        $majors = collect(config('education.majors', []))
            ->map(fn (array $major, string $slug): array => $this->major($slug, $major))
            ->filter(fn (array $major): bool => $major['grades']->isNotEmpty())
            ->values();

        return view('pages.education', [
            'majors' => $majors,
            'guidance' => collect(config('education.guidance', [])),
            'totalCourses' => $majors->sum(fn (array $major): int => $major['course_count']),
            'totalHours' => $majors->sum(fn (array $major): int => $major['weekly_hours']),
        ]);
    }

    /**
     * Shape one configured major for the page.
     *
     * The courses of an unknown grade level, and the courses without a
     * title, are dropped: a typo in the config must not break the table.
     * The courses are grouped by the grade levels that actually have
     * courses, in curriculum order.
     *
     * @param  array<string, mixed>  $major
     * @return array{
     *     slug: string,
     *     title: string,
     *     subtitle: string,
     *     icon: string,
     *     accent: string,
     *     summary: string,
     *     grades: Collection<int, array{level: GradeLevel, courses: Collection<int, array<string, mixed>>}>,
     *     course_count: int,
     *     weekly_hours: int,
     * }
     */
    protected function major(string $slug, array $major): array
    {
        $courses = collect($major['courses'] ?? [])
            ->map(fn (array $course): array => [
                'grade' => GradeLevel::tryFrom((string) ($course['grade'] ?? '')),
                'title' => (string) ($course['title'] ?? ''),
                'weekly_hours' => isset($course['weekly_hours']) ? (int) $course['weekly_hours'] : null,
                'description' => (string) ($course['description'] ?? ''),
            ])
            ->filter(fn (array $course): bool => $course['grade'] instanceof GradeLevel && filled($course['title']))
            ->values();

        $byGrade = $courses->groupBy(fn (array $course): string => $course['grade']->value);

        $grades = collect(GradeLevel::ordered())
            ->map(fn (GradeLevel $level): array => [
                'level' => $level,
                'courses' => $byGrade->get($level->value, collect()),
            ])
            ->filter(fn (array $grade): bool => $grade['courses']->isNotEmpty())
            ->values();

        return [
            'slug' => $slug,
            'title' => (string) ($major['title'] ?? $slug),
            'subtitle' => (string) ($major['subtitle'] ?? ''),
            'icon' => (string) ($major['icon'] ?? 'chip'),
            'accent' => (string) ($major['accent'] ?? 'brand'),
            'summary' => (string) ($major['summary'] ?? ''),
            'grades' => $grades,
            'course_count' => $courses->count(),
            'weekly_hours' => (int) $courses->sum(fn (array $course): int => $course['weekly_hours'] ?? 0),
        ];
    }
}
