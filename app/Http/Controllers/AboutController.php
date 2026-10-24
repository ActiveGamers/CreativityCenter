<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Major;
use App\Models\StaffMember;
use App\Models\Statistic;
use App\Support\SchoolContent;
use Illuminate\Contracts\View\View;

class AboutController extends Controller
{
    /**
     * Show the about page of the school.
     */
    public function index(): View
    {
        return view('pages.about', [
            'intro' => SchoolContent::intro(),
            'statistics' => Statistic::query()
                ->select(['id', 'label', 'value', 'description', 'icon', 'order'])
                ->orderBy('order')
                ->orderBy('id')
                ->get(),
            'achievements' => Achievement::query()
                ->select(['id', 'title', 'description', 'scope', 'rank_label', 'year', 'competition', 'order'])
                ->orderByDesc('year')
                ->orderBy('order')
                ->orderBy('id')
                ->get(),
            'staff' => StaffMember::query()
                ->select(['id', 'name', 'role', 'subject', 'photo', 'phone', 'bio', 'order'])
                ->orderBy('order')
                ->orderBy('id')
                ->get(),
            'majors' => Major::query()
                ->active()
                ->select(['id', 'title', 'slug', 'subtitle', 'summary', 'description', 'icon', 'accent', 'seats'])
                ->withCount('courses')
                ->orderBy('order')
                ->orderBy('id')
                ->get(),
        ]);
    }
}
