<?php

namespace App\Http\Controllers;

use App\Enums\GradeLevel;
use App\Models\Major;
use Illuminate\Contracts\View\View;

class MajorController extends Controller
{
    /**
     * Show the list of the active majors.
     */
    public function index(): View
    {
        $majors = Major::query()
            ->active()
            ->select(['id', 'title', 'slug', 'subtitle', 'summary', 'icon', 'accent', 'seats', 'order'])
            ->withCount('courses')
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        return view('pages.majors', [
            'majors' => $majors,
        ]);
    }

    /**
     * Show a single major with its courses grouped by grade level.
     */
    public function show(Major $major): View
    {
        abort_unless(
            $major->is_active,
            404,
            'رشته مورد نظر یافت نشد یا دیگر در دسترس نیست. لطفاً فهرست رشته‌های تحصیلی را مشاهده کنید.',
        );

        $courses = $major->courses()
            ->select(['id', 'major_id', 'grade', 'title', 'description', 'weekly_hours', 'order'])
            ->get();

        $coursesByGrade = [];

        foreach (GradeLevel::ordered() as $grade) {
            $coursesByGrade[$grade->value] = $courses
                ->where('grade', $grade)
                ->values();
        }

        return view('pages.majors.show', [
            'major' => $major,
            'coursesByGrade' => $coursesByGrade,
        ]);
    }
}
