<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::controller(CategoryController::class)->group(function () {
            Route::get('/', 'readAll');
            Route::post('/', 'createOne');
            Route::get('/{id}', 'readOne');
            Route::put('/{id}', 'updateOne');
            Route::delete('/{id}', 'deleteOne');
        });
    });
    Route::prefix('services')->name('services.')->group(function () {
        Route::controller(ServiceController::class)->group(function () {
            Route::get('/', 'readAll');
            Route::post('/', 'createOne');
            Route::get('/{id}', 'readOne');
            Route::put('/{id}', 'updateOne');
            Route::delete('/{id}', 'deleteOne');
        });
    });
    Route::prefix('providers')->name('providers.')->group(function () {
        Route::controller(ProviderController::class)->group(function () {
            Route::get('/', 'readAll');
            Route::post('/', 'createOne');
            Route::get('/{id}', 'readOne');
            Route::put('/{id}', 'updateOne');
            Route::delete('/{id}', 'deleteOne');
        });
    });
    Route::prefix('locations')->name('locations.')->group(function () {
        Route::controller(LocationController::class)->group(function () {
            Route::get('/', 'readAll');
            Route::post('/', 'createOne');
            Route::get('/{id}', 'readOne');
            Route::put('/{id}', 'updateOne');
            Route::delete('/{id}', 'deleteOne');
        });
    });
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::controller(BookingController::class)->group(function () {
            Route::get('/', 'readAll');
            Route::post('/', 'createOne');
            Route::get('/{id}', 'readOne');
            Route::put('/{id}', 'updateOne');
            Route::delete('/{id}', 'deleteOne');
        });
    });
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::controller(ReviewController::class)->group(function () {
            Route::get('/', 'readAll');
            Route::post('/', 'createOne');
            Route::get('/{id}', 'readOne');
            Route::put('/{id}', 'updateOne');
            Route::delete('/{id}', 'deleteOne');
        });
    });
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::controller(NotificationController::class)->group(function () {
            Route::get('/', 'readAll');
            Route::post('/', 'createOne');
            Route::get('/{id}', 'readOne');
            Route::put('/{id}', 'updateOne');
            Route::delete('/{id}', 'deleteOne');
        });
    });
});
