<?php

namespace Niyama\FileManager\Tests;

use Niyama\FileManager\Providers\FileManagerServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            FileManagerServiceProvider::class,
        ];
    }
}