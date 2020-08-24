<?php

use Illuminate\Support\Facades\Route;
use Silentz\Akismet\Http\Controllers\QueueController;
use Silentz\Akismet\Http\Controllers\QueuesController;

Route::name('akismet.')->prefix('akismet')->group(function () {
    Route::get('/queues', [QueuesController::class, '__invoke'])->name('index');
    Route::get('/queue/{form}', [QueueController::class, '__invoke'])->name('show');
});
