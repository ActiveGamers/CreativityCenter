<?php

namespace App\Models;

use App\Enums\AdmissionStatus;
use App\Enums\GradeLevel;
use Database\Factories\AdmissionRequestFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'student_name',
    'parent_name',
    'phone',
    'email',
    'grade_level',
    'major_id',
    'note',
    'status',
])]
class AdmissionRequest extends Model
{
    /** @use HasFactory<AdmissionRequestFactory> */
    use HasFactory;

    /**
     * The default status of a freshly submitted request.
     */
    protected $attributes = [
        'status' => AdmissionStatus::New->value,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'grade_level' => GradeLevel::class,
            'status' => AdmissionStatus::class,
        ];
    }

    /**
     * The major the student applied for.
     */
    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }
}
