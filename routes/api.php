<?php

use App\Http\Controllers\Admin\NotificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'throttle:60,1'])->prefix('admin')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('api.notifications');
});
