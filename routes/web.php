<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::post('/articles/load-more', [ArticleController::class, 'loadMore'])->name('articles.loadMore');