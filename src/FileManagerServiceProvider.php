<?php

namespace Niyama\FileManager;

use Illuminate\Support\ServiceProvider;
use Niyama\FileManager\Services\FileManagerService;

class FileManagerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(FileManagerService::class, FileManagerService::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/file-manager-web.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}