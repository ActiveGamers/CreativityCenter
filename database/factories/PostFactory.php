<?php

namespace Database\Factories;

use App\Enums\PostCategory;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->randomElement([
            'اعلام برنامه آموزشی جدید',
            'بازدید از آزمایشگاه شبکه',
            'مراسم تجلیل از دانش‌آموزان برتر',
            'شروع ثبت‌نام دوره تابستانه',
            'برگزاری کارگاه نصب و پیکربندی شبکه',
        ]);

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(1, 999999),
            'excerpt' => 'خلاصه‌ای کوتاه از محتوای این مطلب برای نمایش در فهرست اخبار.',
            'body' => 'متن کامل این مطلب که در صفحه جزئیات نمایش داده می‌شود. '.fake()->paragraph(),
            'category' => PostCategory::News,
            'cover_image' => null,
            'published_at' => now()->subDays(fake()->numberBetween(1, 60)),
            'is_published' => true,
        ];
    }

    /**
     * Indicate that the post is not visible on the public website.
     */
    public function unpublished(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_published' => false,
        ]);
    }

    /**
     * Indicate that the post belongs to the given category.
     */
    public function category(PostCategory $category): static
    {
        return $this->state(fn (array $attributes): array => [
            'category' => $category,
        ]);
    }
}
