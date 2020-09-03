<?php

use Faker\Generator as Faker;
use Mabrouk\Mediable\Models\Media;
use Mabrouk\Mediable\Tests\Models\User;

$factory->define(Media::class, function (Faker $faker) {
    $mediable = factory(User::class)->create();

    return [
        'mediable_type' => get_class($mediable),
        'mediable_id' => $mediable->id,
        'path' => imageUrl(640, 480),
        'type' => 'photo',
        'title' => $faker->words(3),
        'description' => $faker->words(15),
        'is_main' => $faker->boolean(100),
    ];
});