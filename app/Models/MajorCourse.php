<?php

namespace App\Models;

use App\Enums\GradeLevel;
use Database\Factories\MajorCourseFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'major_id',
    'grade',
    'title',
    'description',
    'weekly_hours',
    'order',
])]
class MajorCourse extends Model
{
    /** @use HasFactory<MajorCourseFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'grade' => GradeLevel::class,
            'weekly_hours' => 'integer',
            'order' => 'integer',
        ];
    }

    /**
     * The major this course belongs to.
     */
    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }
}
