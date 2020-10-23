<?php

use Illuminate\Support\Facades\Route;
use Silentz\Akismet\Http\Controllers\ActionController;
use Silentz\Akismet\Http\Controllers\ApproveHamController;
use Silentz\Akismet\Http\Controllers\ConfigurationController;
use Silentz\Akismet\Http\Controllers\DiscardSpamController;
use Silentz\Akismet\Http\Controllers\QueueController;
use Silentz\Akismet\Http\Controllers\QueuesController;
use Silentz\Akismet\Http\Controllers\SpamController;

Route::name('akismet.')->prefix('akismet')->group(function () {
    // Route::name('config.')->prefix('config')->group(function () {
    //     Route::get('edit', [ConfigurationController::class, 'edit'])->name('edit');
    //     Route::post('update', [ConfigurationController::class, 'update'])->name('update');
    // });
    Route::prefix('queues')->group(function () {
        Route::name('actions.')->group(function () {
            Route::post('/{form}/submissions/actions', [ActionController::class, 'run'])->name('run');
            Route::get('/{form}/submissions/actions', [ActionController::class, 'bulkActions'])->name('bulk');
        });
        Route::name('queues.')->group(function () {
            Route::get('/', [QueuesController::class, '__invoke'])->name('index');
            Route::get('{form}', [QueueController::class, '__invoke'])->name('show');
        });
        Route::name('spam.')->group(function () {
            Route::get('/{form}/submissions/', [SpamController::class, 'index'])->name('index');
            Route::get('/{form}/submissions/{id}', [SpamController::class, 'show'])->name('show');
            Route::delete('/{form}/submissions/{id}', [DiscardSpamController::class, '__invoke'])->name('discard');
            Route::post('/{form}/submissions/{id}', [ApproveHamController::class, '__invoke'])->name('approve');
        });
    });
});
