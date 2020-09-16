<?php

use Illuminate\Support\Facades\Route;
use Silentz\Akismet\Http\Controllers\CreateSubmissionController;
use Silentz\Akismet\Http\Controllers\DiscardSpamController;
use Silentz\Akismet\Http\Controllers\QueueController;
use Silentz\Akismet\Http\Controllers\QueuesController;

Route::name('akismet.')->prefix('akismet')->group(function () {
    Route::prefix('queues')->group(function () {
        Route::get('/', [QueuesController::class, '__invoke'])->name('index');
        Route::get('{form}', [QueueController::class, '__invoke'])->name('show');
    });
    Route::name('spam.')->prefix('spam')->group(function () {
        Route::delete('/{form}/{id}', [DiscardSpamController::class, '__invoke'])->name('discard');
        Route::post('/{form}/{id}', [CreateSubmissionController::class, '__invoke'])->name('approve');
    });
});
