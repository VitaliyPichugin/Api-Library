<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'api'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', [\App\Http\Controllers\Auth\AuthController::class, 'login']);
        Route::post('logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout']);
        Route::post('refresh', [\App\Http\Controllers\Auth\AuthController::class, 'refresh']);
        Route::post('me', [\App\Http\Controllers\Auth\AuthController::class, 'me']);
        Route::post('signup', [\App\Http\Controllers\Auth\AuthController::class, 'signup']);
    });

    Route::group(['prefix' => 'book'], function () {
        Route::apiResource('', \App\Http\Controllers\BookController::class)
            ->except('create', 'edit', 'index', 'update', 'destroy', 'show');
        Route::delete('/{id}/{lib_id}', [\App\Http\Controllers\BookController::class, 'destroy'])->name('destroy');
        Route::put('/{id}', [\App\Http\Controllers\BookController::class, 'update'])->name('update');
        Route::get('search/{text}', [\App\Http\Controllers\BookController::class, 'search'])->name('search');
        Route::post('like', [\App\Http\Controllers\BookController::class, 'like'])->name('like');
        Route::get('likes', [\App\Http\Controllers\BookController::class, 'getLikes'])->name('likes');
        Route::get('all', [\App\Http\Controllers\BookController::class, 'getAll'])->name('all');
    });

    Route::get('/search/{text}', [\App\Http\Controllers\SearchController::class, 'search'])->name('search');

    Route::group(['prefix' => 'library'], function () {
        Route::apiResource('', \App\Http\Controllers\LibraryController::class)
            ->only('index', 'show', 'store');
        Route::delete('/{id}', [\App\Http\Controllers\LibraryController::class, 'destroy'])->name('destroy');
        Route::post('detach/{book_id}/{lib_id}', [\App\Http\Controllers\LibraryController::class, 'detach'])->name('detach');
        Route::put('attach/{lib_id}', [\App\Http\Controllers\LibraryController::class, 'attach'])->name('attach');
        Route::put('update/{lib_id}', [\App\Http\Controllers\LibraryController::class, 'update'])->name('update');
    });
});
