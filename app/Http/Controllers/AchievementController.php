<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Statistic;
use Illuminate\Contracts\View\View;

class AchievementController extends Controller
{
    /**
     * Show the achievements of the school.
     */
    public function index(): View
    {
        return view('pages.achievements', [
            'achievements' => Achievement::query()
                ->select(['id', 'title', 'description', 'scope', 'rank_label', 'year', 'competition', 'order'])
                ->orderByDesc('year')
                ->orderBy('order')
                ->orderBy('id')
                ->get(),
            'statistics' => Statistic::query()
                ->select(['id', 'label', 'value', 'description', 'icon', 'order'])
                ->orderBy('order')
                ->orderBy('id')
                ->get(),
        ]);
    }
}
