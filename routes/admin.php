<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->controller(AdminController::class)->group(function () {
    Route::get('/', 'index')->name('admin.index');
    Route::get('user', 'user')->name('admin.user');
    Route::post('/assume/{user}', 'assume')->name('admin.assume');
});
