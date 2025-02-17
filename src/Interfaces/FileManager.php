<?php

namespace Niyama\FileManager\Interfaces;

use Niyama\FileManager\Enums\FilePlatformEnum;
use Niyama\FileManager\Enums\FileTypeEnum;
use Niyama\FileManager\Models\File;

interface FileManager
{

    public function store($file, FileTypeEnum $fileType, FilePlatformEnum|null $platform = null, $position = null);

    public function delete(File $file);
}
