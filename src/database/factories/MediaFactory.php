<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'path',
            'type' => $this->faker->randomElement(['photo', 'file', 'video']),
            'size' => $this->faker->numberBetween(50, 2048),
            'title' => $this->faker->sentence(5),
            'description' => $this->faker->paragraph(2),
            'priority' => $this->faker->numberBetween(1, 9999),
            'is_main' => false,
        ];
    }
}
