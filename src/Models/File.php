<?php

namespace Niyama\FileManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Niyama\FileManager\Casts\FileData;
use Niyama\FileManager\Enums\FileTypeEnum;

class File extends Model
{
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'model',
        'type',
        'position',
        'data',
    ];

    protected function casts(): array
    {
        return [
            'type' => FileTypeEnum::class,
            'data' => FileData::class,
        ];
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

//    static function getModelType($modelType)
//    {
//        $mas = explode("\\", $modelType);
//        return end($mas);
//    }
//
//    static function getModel($name_table){
//        $model = '\App\Models\\'.\ucfirst(\Illuminate\Support\Str::singular($name_table));
//        $model = new $model();
//        return $model;
//    }

}