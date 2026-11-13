<?php

namespace App\Models;

use App\Enums\PostCategory;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title',
    'slug',
    'excerpt',
    'body',
    'category',
    'cover_image',
    'published_at',
    'is_published',
])]
class Post extends Model
{
    /** @use HasFactory<PostFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'category' => PostCategory::class,
            'published_at' => 'datetime',
            'is_published' => 'boolean',
        ];
    }

    /**
     * Scope the query to the posts that are publicly visible.
     */
    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true)
            ->where(function (Builder $query): void {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    /**
     * Determine whether the post is publicly visible right now.
     *
     * It applies the same rule as the `published` scope, so a single post and
     * the list of the posts always agree on what the public can reach.
     */
    public function isPubliclyVisible(): bool
    {
        return $this->is_published
            && ($this->published_at === null || $this->published_at->lessThanOrEqualTo(now()));
    }
}
