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

    'prefix' => 'api',

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

    /*
    |--------------------------------------------------------------------------
    | Routes publish subdirectory
    |--------------------------------------------------------------------------
    |
    | Here you may specify the subdirectory where the package routes should be
    | published inside the project's routes folder. This allows you to customize the location of the published
    | routes files.
    |
    */
    # eg: 'routes_publish_subdirectory' => 'custom/',
    'routes_publish_subdirectory' => '',

    /*
    |--------------------------------------------------------------------------
    | Load Routes
    |--------------------------------------------------------------------------
    |
    | This option controls whether the package routes should be loaded.
    | Set this value to true to load the routes, or false to disable them.
    |
    */
    'load_routes' => true,
];
