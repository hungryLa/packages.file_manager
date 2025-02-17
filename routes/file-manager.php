<?php

use Illuminate\Support\Facades\Route;
use Niyama\FileManager\Http\Controllers\FileManagerController;

Route::controller(FileManagerController::class)
    ->prefix('file-manager')
    ->group(function (){

        Route::post('uploadFiles', 'uploadFiles')
            ->name('file-manager.uploadFiles');

        Route::post('uploadCropImage', 'uploadCropImage')
            ->name('file-manager.uploadCropImage');

        Route::post('dynamicSorting','dynamicSorting')
            ->name('file-manager.dynamicSorting');

        Route::delete('deleteImagesThroughCheckBox', 'deleteFilesThroughCheckBox')
            ->name('file-manager.deleteFilesThroughCheckBox');
});