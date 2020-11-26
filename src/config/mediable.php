<?php

return [
    'base_path' => 'public/',

    'photos' => [
        'path' => env('APP_PHOTOS_PATH', 'photos/'),
    ],

    'files' => [
        'path' => env('APP_FILES_PATH', 'files/'),
    ],

    'videos' => [
        'path' => env('APP_VIDEOS_PATH', 'videos/'),
    ],

    'prefix' => '',

    'middleware' => [
        //
    ],

    /**
     * syntax [
     *      'specific_directory_path_included_in_media_file_path' => [
     *          'middleware_1',
     *          'middleware_2',
     *      ],
     * ]
     * each path may apply its own middlewares
     * active only when reading a media file from storage
     */
    'protected_internal_media_base_paths' => [
        // '' => [],
    ],
];
