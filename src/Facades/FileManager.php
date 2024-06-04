<?php

namespace Niyama\FileManager\Facades;

use Illuminate\Support\Facades\Facade;
use Niyama\FileManager\Services\FileManagerService;

class FileManager extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return FileManagerService::class;
    }


}
