<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ContactRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContactRequest>
 */
class ContactRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'subject' => fake()->sentence(),
            'message' => fake()->paragraphs(3, true),
            'request_type' => fake()->randomElement(['booking', 'general', 'collaboration', 'other']),
            'status' => 'new',
            'preferred_language' => fake()->randomElement(['ru', 'en', 'eo']),
            'ip_address' => fake()->ipv4(),
        ];
    }

    public function booking(): static
    {
        return $this->state(fn (array $attributes) => [
            'request_type' => 'booking',
            'subject' => 'Booking Request: '.fake()->sentence(3),
        ]);
    }
}
