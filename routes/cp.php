<?php

use Illuminate\Support\Facades\Route;
use Silentz\Akismet\Http\Controllers\QueueController;

Route::name('akismet.')->prefix('akismet')->group(function () {
    Route::view('/queue', 'akismet::cp.queues.index')->name('index');
    Route::get('/queue/{form}', [QueueController::class, '__invoke'])->name('show');
});
