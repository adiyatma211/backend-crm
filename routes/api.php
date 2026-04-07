<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Api\StatController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ProcessInfoController;
use App\Http\Controllers\Api\SettingsController;

Route::prefix('v1')->group(function () {
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/{slug}', [ProjectController::class, 'show']);
    
    Route::get('/testimonials', [TestimonialController::class, 'index']);
    
    Route::get('/stats', [StatController::class, 'index']);
    
    Route::get('/clients', [ClientController::class, 'index']);
    
    Route::get('/process-info', [ProcessInfoController::class, 'index']);
    
    Route::get('/settings', [SettingsController::class, 'index'])->name('api.settings.index');
    Route::get('/settings/logo', [SettingsController::class, 'getLogo'])->name('api.settings.getLogo');
    Route::put('/settings', [SettingsController::class, 'update'])->name('api.settings.update');
});
