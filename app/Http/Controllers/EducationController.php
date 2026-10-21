<?php

namespace App\Http\Controllers;

use App\Enums\GradeLevel;
use App\Models\Major;
use App\Models\MajorCourse;
use Illuminate\Contracts\View\View;

class EducationController extends Controller
{
    /**
     * Show the curriculum of every grade level.
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

        $courses = MajorCourse::query()
            ->select(['id', 'major_id', 'grade', 'title', 'description', 'weekly_hours', 'order'])
            ->whereIn('major_id', $majors->pluck('id'))
            ->with('major:id,title,slug')
            ->orderBy('major_id')
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        $coursesByGrade = [];

        foreach (GradeLevel::ordered() as $grade) {
            $coursesByGrade[$grade->value] = $courses
                ->where('grade', $grade)
                ->values();
        }

        return view('pages.education', [
            'majors' => $majors,
            'coursesByGrade' => $coursesByGrade,
        ]);
    }
}
