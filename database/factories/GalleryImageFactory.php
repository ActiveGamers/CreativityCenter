<?php

namespace Database\Factories;

use App\Models\GalleryImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GalleryImage>
 */
class GalleryImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $number = fake()->unique()->numberBetween(1, 6);

        return [
            'title' => 'تصویر آرشیو هنرستان',
            'caption' => 'تصویر نمونه که جایگزین تصاویر واقعی مدرسه خواهد شد.',
            'path' => '/images/gallery/placeholder-'.$number.'.svg',
            'album' => 'آموزشی',
            'order' => fake()->numberBetween(1, 10),
            'is_published' => true,
        ];
    }
}
