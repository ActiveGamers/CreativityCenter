<?php

namespace App\Models;

use Database\Factories\MajorFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'title',
    'slug',
    'subtitle',
    'summary',
    'description',
    'icon',
    'accent',
    'seats',
    'order',
    'is_active',
])]
class Major extends Model
{
    /** @use HasFactory<MajorFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'seats' => 'integer',
            'order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * The courses taught in this major.
     */
    public function courses(): HasMany
    {
        return $this->hasMany(MajorCourse::class)
            ->orderBy('order')
            ->orderBy('id');
    }

    /**
     * The admission requests submitted for this major.
     */
    public function admissionRequests(): HasMany
    {
        return $this->hasMany(AdmissionRequest::class);
    }

    /**
     * Scope the query to the majors that are visible on the website.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }
}
