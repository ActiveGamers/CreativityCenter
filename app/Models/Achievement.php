<?php

namespace App\Models;

use App\Enums\AchievementScope;
use Database\Factories\AchievementFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title',
    'description',
    'scope',
    'rank_label',
    'year',
    'competition',
    'order',
])]
class Achievement extends Model
{
    /** @use HasFactory<AchievementFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'scope' => AchievementScope::class,
            'year' => 'integer',
            'order' => 'integer',
        ];
    }
}
