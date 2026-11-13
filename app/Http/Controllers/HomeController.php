<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Major;
use App\Models\Post;
use App\Models\StaffMember;
use App\Models\Statistic;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    /**
     * Show the home page of the school.
     */
    public function index(): View
    {
        return view('pages.home', [
            'statistics' => Statistic::query()
                ->select(['id', 'label', 'value', 'description', 'icon', 'order'])
                ->orderBy('order')
                ->orderBy('id')
                ->get(),
            'majors' => Major::query()
                ->active()
                ->select(['id', 'title', 'slug', 'subtitle', 'summary', 'icon', 'accent', 'seats', 'order'])
                ->withCount('courses')
                ->orderBy('order')
                ->orderBy('id')
                ->get(),
            'achievements' => Achievement::query()
                ->select(['id', 'title', 'description', 'scope', 'rank_label', 'year', 'competition', 'order'])
                ->orderByDesc('year')
                ->orderBy('order')
                ->orderBy('id')
                ->limit(3)
                ->get(),
            'posts' => Post::query()
                ->published()
                ->select(['id', 'title', 'slug', 'excerpt', 'category', 'cover_image', 'published_at'])
                ->orderByDesc('published_at')
                ->orderByDesc('id')
                ->limit(3)
                ->get(),
            'staff' => StaffMember::query()
                ->select(['id', 'name', 'role', 'subject', 'photo', 'phone', 'bio', 'order'])
                ->orderBy('order')
                ->orderBy('id')
                ->get(),
        ]);
    }
}
