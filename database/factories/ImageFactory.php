<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'album_id' => null,
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'file_name' => fake()->imageUrl(1920, 1080, 'theater', true, 'stage'),
            'mime_type' => 'image/jpeg',
            'file_size' => fake()->numberBetween(500000, 5000000),
            'disk' => 'public',
            'width' => fake()->randomElement([1920, 2560, 3840]),
            'height' => fake()->randomElement([1080, 1440, 2160]),
            'is_watermarked' => true,
            'status' => fake()->randomElement(['draft', 'published', 'archived']),
            'sort_order' => fake()->numberBetween(1, 1000),
            'metadata' => [
                'camera' => fake()->randomElement(['Canon EOS R5', 'Sony A7 IV', 'Nikon Z9']),
                'lens' => fake()->randomElement(['24-70mm f/2.8', '70-200mm f/2.8']),
                'iso' => fake()->randomElement([400, 800, 1600, 3200]),
                'aperture' => fake()->randomElement(['f/2.8', 'f/4', 'f/5.6']),
            ],
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'is_watermarked' => false,
        ]);
    }

    public function forAlbum(int $albumId): static
    {
        return $this->state(fn (array $attributes) => [
            'album_id' => $albumId,
        ]);
    }
}
