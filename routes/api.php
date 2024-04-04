<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SocialMediaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserLikeArticleController;
use App\Http\Controllers\UserLikesController;
use Illuminate\Support\Facades\Route;


Route::prefix('/admin')->group(function (){

    //Kullanıcı işlemleri
    Route::prefix('/users')->group(function () {
        Route::get('/get-all',[UserController::class,'getAll']);
        Route::get('/{id}',[UserController::class,'getByDetail']);
        Route::post('/update',[UserController::class,'update']);
        Route::post('/change-password',[UserController::class,'changePassword']);
        Route::post('/change-status',[UserController::class,'changeStatus']);
    });

    //Kategori işlemleri
    Route::prefix('/categories')->group(function () {
        Route::get('/get-all',[CategoryController::class,'getAll']);
        Route::get('/{id}/get-all-articles-by-category',[CategoryController::class,'getAllArticlesByCategory']);
        Route::post('/create',[CategoryController::class,'create']);
        Route::post('/update',[CategoryController::class,'update']);
        Route::delete('/{id}/delete',[CategoryController::class,'delete']);
        Route::get('/{id}',[CategoryController::class,'getByDetail']);
    });

    //Makle işlemleri
    Route::prefix('/articles')->group(function () {
        Route::post('/create',[ArticleController::class,'create']);
        Route::post('/update',[ArticleController::class,'update']);
        Route::delete('/{id}/delete',[ArticleController::class,'delete']);
        Route::get('/{id}',[ArticleController::class,'getByDetail']);
    });

    //Yorum işlemleri
    Route::prefix('/comments')->group(function () {
        Route::post('/create',[CommentController::class,'create']);
        Route::post('/update',[CommentController::class,'update']);
        Route::delete('/{id}/delete',[CommentController::class,'delete']);
        Route::post('/like-count',[CommentController::class,'likeComment']);
    });

    //Ayar işlemleri
    Route::prefix('/settings')->group(function () {
        Route::get('/get-settings',[SettingController::class,'getSettings']);
        Route::post('/update',[SettingController::class,'update']);
    });

    //Sosyal medya işlemleri
    Route::prefix('/social-media')->group(function () {
        Route::post('/get',[SocialMediaController::class,'getSocialMedia']);
        Route::post('/create',[SocialMediaController::class,'create']);
        Route::post('/update',[SocialMediaController::class,'update']);
        Route::delete('/{id}/delete',[SocialMediaController::class,'delete']);
    });
});

//Kullanıcı işlemleri
Route::prefix('/auth')->group(function () {
    Route::post('/register',[AuthController::class,'register']);
    Route::post('/login',[AuthController::class,'login']);
});

Route::post('/article-like-status',[UserLikesController::class,'articleLikeStatus']);
Route::post('/comment-like-status',[UserLikesController::class,'commentLikeStatus']);
