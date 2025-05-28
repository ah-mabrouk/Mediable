<?php

namespace Mabrouk\Mediable\Factories;

use Mabrouk\Mediable\Models\Media;
use Mabrouk\Mediable\Models\MediaMeta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory
 */
class MediaMetaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MediaMeta::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'media_id' => Media::factory(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (MediaMeta $mediaMeta) {
            $mediaMeta->translate([
                'meta_title' => $this->faker->sentence(5),
                'alternative_text' => $this->faker->paragraph(2),
            ], 'en')->translate([
                'meta_title' => 'ar_' . $this->faker->sentence(5),
                'alternative_text' => 'ar_' . $this->faker->paragraph(2),
            ], 'ar');
        });
    }
}
