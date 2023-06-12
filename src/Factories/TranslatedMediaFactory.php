<?php

namespace Mabrouk\Mediable\Factories;

use Mabrouk\Mediable\Models\TranslatedMedia;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TranslatedMediaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TranslatedMedia::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'path' => 'https://via.placeholder.com/1024x300?text=media_file',
            'type' => $this->faker->randomElement(['photo', 'file', 'video', 'voice', 'url']),
            'media_group_name' => null,
            'size' => $this->faker->numberBetween(50, 2048),
            'priority' => $this->faker->numberBetween(1, 9999),
            'is_main' => false,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (TranslatedMedia $translatedMedia) {
            $translatedMedia->translate([
                'title' => $this->faker->sentence(5),
                'description' => $this->faker->paragraph(2),
            ], 'en')->translate([
                'title' => 'ar_' . $this->faker->sentence(5),
                'description' => 'ar_' . $this->faker->paragraph(2),
            ], 'ar');
        });
    }
}
