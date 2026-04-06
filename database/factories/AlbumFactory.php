<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Album;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Album>
 */
class AlbumFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->words(3, true);

        return [
            'parent_id' => null,
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => fake()->sentence(),
            'cover_image_path' => null,
            'is_published' => fake()->boolean(80),
            'sort_order' => fake()->numberBetween(1, 100),
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => true,
        ]);
    }

    public function withParent(Album $parent): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => $parent->id,
        ]);
    }
}
