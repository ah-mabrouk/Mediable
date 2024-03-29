<?php

namespace Mabrouk\Mediable;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class MediableServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        require_once __DIR__ . '/Helpers/MediableHelperFunctions.php';

        $this->registerRoutes();

        if ($this->app->runningInConsole()) {
            /**
             * Mediable Migrations
             */
            $migrationFiles = [];

            switch (true) {
                case ! class_exists('CreateMediaTable') :
                    $migrationFiles[__DIR__ . '/database/migrations/create_media_table.php.stub'] = database_path('migrations/' . date('Y_m_d_His', time()) . '_create_media_table.php');
                case ! class_exists('CreateTranslatedMediaTable') :
                    $migrationFiles[__DIR__ . '/database/migrations/create_translated_media_table.php.stub'] = database_path('migrations/' . date('Y_m_d_His', time()) . '_create_translated_media_table.php');
                case ! class_exists('CreateTranslatedMediaTranslationsTable') :
                    $migrationFiles[__DIR__ . '/database/migrations/create_translated_media_translations_table.php.stub'] = database_path('migrations/' . date('Y_m_d_His', time()) . '_create_translated_media_translations.php');
            }

            if (\count($migrationFiles) > 0) {
                $this->publishes($migrationFiles, 'mediable-migrations');
            }

            /**
             * Mediable Config
             */
            $this->publishes([
                __DIR__ . '/config/mediable.php' => config_path('mediable.php'),
            ], 'mediable-config');
        }
    }

    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/routes/mediable_routes.php');
        });
    }

    protected function routeConfiguration()
    {
        return [
            'namespace' => 'Mabrouk\Mediable\Http\Controllers',
            'prefix' => config('mediable.prefix'),
            'middleware' => config('mediable.middleware'),
        ];
    }
}
