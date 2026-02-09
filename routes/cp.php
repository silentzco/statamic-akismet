<?php

use Illuminate\Support\Facades\Route;
use Silentz\Akismet\Http\Controllers\ActionController;
use Silentz\Akismet\Http\Controllers\API\ListSpamController as ListSpamApiController;
use Silentz\Akismet\Http\Controllers\Ham\StoreHamController;
use Silentz\Akismet\Http\Controllers\ShowQueuesController;
use Silentz\Akismet\Http\Controllers\Spam\ListSpamController;
use Silentz\Akismet\Http\Controllers\Spam\ShowSpamController;

Route::name('akismet.')->prefix('akismet')->group(function () {
    Route::name('api.')->prefix('/api/queues/{form}/spam')->group(function () {
        Route::get('/', ListSpamAPIController::class)->name('index');
    });

    Route::prefix('queues')->group(function () {
        Route::name('actions.')->prefix('{form}/spam/actions')->group(function () {
            Route::post('/', [ActionController::class, 'run'])->name('run');
            Route::post('list', [ActionController::class, 'bulkActions'])->name('bulk');
        });

        Route::name('ham.')->prefix('{form}/ham')->group(function () {
            Route::post('{id}', [StoreHamController::class, '__invoke'])->name('store');
        });

        Route::name('spam.')->prefix('{form}/spam')->group(function () {
            Route::get('/', ListSpamController::class)->name('index');
            Route::get('{id}', [ShowSpamController::class, '__invoke'])->name('show');
        });

        Route::name('queues.')->group(function () {
            Route::get('/', ShowQueuesController::class)->name('index');
        });
    });
});
