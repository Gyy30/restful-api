<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\post>
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
        return [
            'image'   => $this->faker->imageUrl(640, 480, 'tech', true),
            'title'   => $this->faker->sentence(),
            'content' => $this->faker->paragraph(3, true),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
