<?php

namespace Niyama\FileManager\Facades;

use Illuminate\Support\Facades\Facade;
use Niyama\FileManager\Services\TestService;

class Test extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return TestService::class;
    }

}