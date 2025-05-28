<?php

namespace Mabrouk\Mediable;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class MediableServiceProvider extends ServiceProvider
{
    private $packageMigrations = [
        'create_media_table',
        'create_media_meta_table',
        'create_media_meta_translations_table',
    ];

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
             * Migrations
             */
            $migrationFiles = $this->migrationFiles();
            if (\count($migrationFiles) > 0) {
                $this->publishes($migrationFiles, 'media_migrations');
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

    protected function migrationFiles()
    {
        $migrationFiles = [];

        foreach ($this->packageMigrations as $migrationName) {
            if (! $this->migrationExists($migrationName)) {
                $migrationFiles[__DIR__ . "/database/migrations/{$migrationName}.php.stub"] = database_path('migrations/' . date('Y_m_d_His', time()) . "_{$migrationName}.php");
            }
        }
        return $migrationFiles;
    }

    protected function migrationExists($migrationName)
    {
        $path = database_path('migrations/');
        $files = \scandir($path);
        $pos = false;
        foreach ($files as &$value) {
            $pos = \strpos($value, $migrationName);
            if ($pos !== false) return true;
        }
        return false;
    }    
}
