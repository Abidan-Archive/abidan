<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->controller(AdminController::class)->group(function () {
    Route::get('/', 'index')->name('admin.index');

    Route::get('/user', 'user')->name('admin.user');
    Route::get('/review', 'reviewIndex')->name('admin.review');
    Route::post('/assume/{user}', 'assume')->name('admin.assume');
    Route::post('/query', 'query')->name('admin.query');
    Route::post('/ban', 'ban')->name('admin.ban');
    Route::get('/source', 'source')->name('admin.source');

    // Route::get('/report', 'report')->name('admin.report');
});
