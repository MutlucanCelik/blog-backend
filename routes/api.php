<?php

use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('/admin')->group(function (){

    Route::prefix('/users')->group(function () {
        Route::get('/index',[UserController::class,'show']);
    });

    Route::prefix('/categories')->group(function () {
        Route::post('/create',[CategoryController::class,'create']);
    });
});
