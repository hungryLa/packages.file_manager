<?php

use Illuminate\Support\Facades\Route;
use Niyama\FileManager\Http\Controllers\FileManagerController;

Route::group(['prefix' => 'file-manager'], function (){

    Route::get('index', [FileManagerController::class, 'index'])
        ->name('file-manager.index');

});