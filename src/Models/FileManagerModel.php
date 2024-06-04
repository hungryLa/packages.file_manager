<?php

namespace Niyama\FileManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class FileManagerModel extends Model
{
    protected $guarded = [];
    const TYPE = [
        'images' => 'images',
        'documents' => 'documents',
    ];
    const MAX_FILES = [
        'images' => 4,
        'documents' => 1,
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    static function getPosition($modelType, $modelId, $fileType)
    {
        if(count(FileManagerModel::all()) != 0){
            $last_file = FileManagerModel::where([
                'model_type' => $modelType,
                'model_id' => $modelId,
                'type' => $fileType,
            ])->orderBy('position','desc')->first();

            if($last_file){
                $position = $last_file->position + 1;
            }else{
                $position = 1;
            }
        }else{
            $position = 1;
        }

        return $position;
    }

    static function getModelType($modelType)
    {
        $mas = explode("\\", $modelType);
        return end($mas);
    }

    static function getModel($name_table){
        $model = '\App\Models\\'.\ucfirst(\Illuminate\Support\Str::singular($name_table));
        $model = new $model();
        return $model;
    }

}