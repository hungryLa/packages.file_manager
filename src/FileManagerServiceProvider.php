<?php

namespace Niyama\FileManager;

use Illuminate\Support\ServiceProvider;
use Niyama\FileManager\Services\FileManagerService;

class FileManagerServiceProvider extends ServiceProvider
{
    public function register(): void
    {

    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__. '/../config/file-manager.php' => config_path('file-manager.php'),
        ]);
        $this->loadRoutesFrom(__DIR__ . '/../routes/file-manager.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}