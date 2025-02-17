<?php
use Niyama\FileManager\Enums\FileTypeEnum;
return [

    'max_allowed' => [
        FileTypeEnum::IMAGE->value => 2,
        FileTypeEnum::DOCUMENT->value => 1,
    ],

    // The coefficient by which the image sizes are divided to reduce the size of the uploaded file.
    // Recommended values are in the range [1, 3]
    'scale_image' => 1,

    // Image quality value from 0 to 100
    'image_quality' => 70,
];