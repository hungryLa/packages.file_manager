<?php

namespace Niyama\FileManager\Helpers;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File as FileFacade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Niyama\FileManager\Enums\FileTypeEnum;
use Niyama\FileManager\Enums\FilePlatformEnum;
use Niyama\FileManager\Models\File;
use Exception;


class FileManager
{
    private $model = null;
    protected $modelType = null;
    protected $modelTypeShort = null;
    protected $path = null;
    protected $uploadPath = null;
    protected $imageManager = null;

    public function __construct(string $modelType, int $modelId)
    {
        $this->modelType = $modelType;

        $this->setModelTypeShort();
        $this->initModel($modelId);

        $this->imageManager = new ImageManager(new Driver());
    }

    public function modelType(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->modelType,
        );
    }

    public function storeFile($file, FileTypeEnum $fileType, FilePlatformEnum|null $platform = null, $position = null)
    {
        self::checkCurrentCountFiles($fileType);

        $path = $this->setPath($fileType, $platform);
        $format = $file->getClientOriginalExtension();

        if(!$position){
            $position = $this->getPosition($fileType);
        }

        if ($format == 'jpg' || $format == 'jpeg' || $format == 'png' || $format == 'webp') {

            $name = uniqid() . time() . rand(10, 1000000);
            $name = Str::finish($name, '.webp');
            $this->uploadFile($file, $name);

        }

        return File::create([
            'model_type' => $this->modelType,
            'model_id' => $this->model->id,
            'type' => $fileType,
            'platform' => $platform,
            'position' => $position,
            'name' => $name,
            'original_name' => $file->getClientOriginalName(),
            'path' => $this->uploadPath,
        ]);
    }

    public function storeCropImage($files)
    {
        $success = false;
        $type = FileTypeEnum::IMAGE;
        $position = $this->getPosition($type);

        $name = uniqid() . time() . rand(10, 1000000);
        $name = Str::finish($name, '.webp');

        foreach ($files as $platform => $file) {
            $format = $file->getClientOriginalExtension();

            $platform = FilePlatformEnum::tryFrom($platform);

            $this->setPath($type, $platform);

            if ($format == 'jpg' || $format == 'jpeg' || $format == 'png' || $format == 'webp') {

                $this->uploadFile($file, $name);

            }

            $success = File::create([
                'model_type' => $this->modelType,
                'model_id' => $this->model->id,
                'type' => $type,
                'platform' => $platform,
                'position' => $position,
                'name' => $name,
                'original_name' => $file->getClientOriginalName(),
                'path' => $this->uploadPath,
            ]);

            if (!$success){
                break;
            }

        }

        return $success;
    }

    public function getPosition(FileTypeEnum $fileType): int
    {
        $position = 1;
        if($fileType == FileTypeEnum::IMAGE){
            $position = $this->model->images()->count() + 1;
        }elseif ($fileType == FileTypeEnum::DOCUMENT){
            $position = $this->model->documents()->count() + 1;
        }
        return $position;
    }

    public function downlandFile(File $file)
    {
        $path = str_replace('storage/', '', $file->path);

        $path =  Storage::disk('public')->path($path);

        return response()->download($path, $file->original_name);
    }
    public static function deleteFile(File $file)
    {
        $record = $file;

        FileFacade::delete($file->path);

        $filesAfter = File::where([
            'model_type' => $file->model_type,
            'model_id' => $file->model_id,
            'type' => $file->type,
        ])->where('position', '>', $file->position)->orderBy('position', 'asc')->get();

        if($file->type == FileTypeEnum::IMAGE){
            $success = $file->getSprites()->each(function ($sprite) {
                FileFacade::delete($sprite->path);
                $sprite->delete();
            });
        }else{
            $success = $file->delete();
        }

        foreach ($filesAfter as $fileAfter) {
            $fileAfter->update([
                'position' => $fileAfter->position - 1,
            ]);
        }

        self::deleteFolder($record);

        if ($success) {
            return response()->json([
                'message' => __('other.The file was successfully deleted')
            ]);
        }
    }

    private function uploadFile(File $file, string $name)
    {
        $img = $this->imageManager->read($file);

        $widthImage = $img->width() / config('file-manager.scale_image');
        $heightImage = $img->height() / config('file-manager.scale_image');

        $img->scale($widthImage, $heightImage);

        $img->toWebp(config('file-manager.image_quality'))
            ->save($this->path.'/'.$name);

        $this->uploadFile = $this->path . '/' . $name;
    }
    private function setPath(FileTypeEnum $fileType, FilePlatformEnum|null $platform = null): string
    {

        $upload_folder = $this->modelTypeShort .'/'. $this->model->id .'/'. $fileType->value;

        if($platform){
            $upload_folder = $upload_folder .'/'. $platform->value;
        }

        $this->path = 'storage/' .$upload_folder;

        if (!file_exists($this->path)) {
            FileFacade::makeDirectory($this->path, $mode = 0755, true, true);
        }

        return $this->path;
    }

    private function setModelTypeShort(): void
    {
        $mas = explode("\\", $this->modelType);
        $this->modelTypeShort = end($mas);
    }

    /**
     * @throws Exception
     */
    private function initModel(int $modelId): void
    {
        $model = new $this->modelType();
        if(!$model){
            throw new Exception(__('file-manager.no_model'), Response::HTTP_NOT_FOUND);
        }
        try{
            $this->model = $model->findOrFail($modelId);
        }catch (ModelNotFoundException $e){
            throw new Exception(__('file-manager.no_record', [
                'model' => $this->modelTypeShort,
            ]), Response::HTTP_NOT_FOUND);
        }
    }

    private static function deleteFolder($file){

        $platform = '';

        $str_search = null;

        if($file->platform != null){
            $platform = '/'. $file->platform->value;
        }

        if (count(File::where([
                'model_type' => $file->model_type,
                'model_id' => $file->model_id,
            ])->get()) == 0) {

            $str_search = '/' . $file->type->value . $platform . '/' . $file->name;

        } elseif (count(File::where([
                    'model_type' => $file->model_type,
                    'model_id' => $file->model_id,
                    'type' => $file->type
                ])->get()) == 0) {
            $str_search = $platform. '/' . $file->name;
        }
        if($str_search){
            $result = str_replace($str_search, '', $file->path);
            FileFacade::deleteDirectory($result);
        }

    }

    /**
     * @throws Exception
     */
    public function checkCurrentCountFiles(FileTypeEnum $fileType): void
    {
        $count_files = $this->model->files($fileType)
            ->whereIn('platform', [null, FilePlatformEnum::DESKTOP])
            ->count();

        if(
            defined( $this->modelType. '::MAX_FILES') &&
            array_key_exists($fileType->value, $this->model::MAX_FILES)
        ){
            $max = $this->model::MAX_FILES[$fileType->value];
        }else{
            $max = self::MAX_FILES[$fileType->value];
        }

        if($count_files >= $max){
            throw new Exception(__('file-manager.limit'), 400);
        }

    }

}
