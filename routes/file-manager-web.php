<?php

use Illuminate\Support\Facades\Route;
use Niyama\FileManager\Http\Controllers\FileManagerController;

Route::group(['prefix' => 'file-manager'], function (){

    Route::get('hello', [FileManagerController::class, 'index']);

});