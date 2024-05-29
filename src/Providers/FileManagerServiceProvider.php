<?php

namespace Niyama\FileManager\Providers;

use Illuminate\Support\ServiceProvider;
use Niyama\FileManager\Services\TestService;

class FileManagerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TestService::class, TestService::class);
    }

    public function boot(): void
    {

    }
}