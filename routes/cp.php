<?php

use Illuminate\Support\Facades\Route;
use Silentz\Akismet\Http\Controllers\ActionController;
use Silentz\Akismet\Http\Controllers\API\ListSpamController as ListSpamApiController;
use Silentz\Akismet\Http\Controllers\Config\EditConfigController;
use Silentz\Akismet\Http\Controllers\Config\UpdateConfigController;
use Silentz\Akismet\Http\Controllers\Ham\StoreHamController;
use Silentz\Akismet\Http\Controllers\ShowQueuesController;
use Silentz\Akismet\Http\Controllers\Spam\DestroySpamController;
use Silentz\Akismet\Http\Controllers\Spam\ListSpamController;
use Silentz\Akismet\Http\Controllers\Spam\ShowSpamController;

Route::name('akismet.')->prefix('akismet')->group(function () {
    Route::name('api.')->prefix('/api/queues/{form}/spam')->group(function () {
        Route::get('/', [ListSpamAPIController::class, '__invoke'])->name('index');
    });

    Route::name('config.')->prefix('config')->group(function () {
        Route::get('edit', [EditConfigController::class, '__invoke'])->name('edit');
        Route::post('update', [UpdateConfigController::class, '__invoke'])->name('update');
    });
    Route::prefix('queues')->group(function () {
        Route::name('actions.')->prefix('{form}/spam')->group(function () {
            Route::post('actions', [ActionController::class, 'run'])->name('run');
            Route::get('actions', [ActionController::class, 'bulkActions'])->name('bulk');
        });

        Route::name('ham.')->prefix('{form}/ham')->group(function () {
            Route::post('{id}', [StoreHamController::class, '__invoke'])->name('store');
        });

        Route::name('spam.')->prefix('{form}/spam')->group(function () {
            Route::get('/', [ListSpamController::class, '__invoke'])->name('index');
            Route::get('{id}', [ShowSpamController::class, '__invoke'])->name('show');
            Route::delete('{id}', [DestroySpamController::class, '__invoke'])->name('destroy');
        });

        Route::name('queues.')->group(function () {
            Route::get('/', [ShowQueuesController::class, '__invoke'])->name('index');
        });
    });
});
