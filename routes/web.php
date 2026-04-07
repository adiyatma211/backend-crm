<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\StatController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProcessInfoController;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::get('/analytics', [DashboardController::class, 'analytics'])->name('analytics');
    
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::post('/', [SettingsController::class, 'store'])->name('store');
        Route::put('/', [SettingsController::class, 'update'])->name('update');
        Route::post('/upload-logo', [SettingsController::class, 'uploadLogo'])->name('upload-logo');
        Route::post('/reset-logo', [SettingsController::class, 'resetLogo'])->name('reset-logo');
        Route::post('/delete-logo', [SettingsController::class, 'deleteLogo'])->name('delete-logo');
        Route::get('/logo-preview', [SettingsController::class, 'getLogoPreview'])->name('logo-preview');
    });

    Route::prefix('project-management')->name('projects.')->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->name('index');
        Route::get('/create', [ProjectController::class, 'create'])->name('create');
        Route::post('/', [ProjectController::class, 'store'])->name('store');

        // Dynamic routes should come AFTER static routes
        Route::get('/{project}', [ProjectController::class, 'show'])->name('show')
            ->where('project', '[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}');
        Route::get('/{project}/edit', [ProjectController::class, 'edit'])->name('edit')
            ->where('project', '[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}');
        Route::put('/{project}', [ProjectController::class, 'update'])->name('update')
            ->where('project', '[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}');
        Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('destroy')
            ->where('project', '[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}');
    });

    Route::prefix('testimonials')->name('testimonials.')->group(function () {
        Route::get('/', [TestimonialController::class, 'index'])->name('index');
        Route::get('/create', [TestimonialController::class, 'create'])->name('create');
        Route::post('/', [TestimonialController::class, 'store'])->name('store');

        // Dynamic routes should come AFTER static routes
        Route::get('/{testimonial}/edit', [TestimonialController::class, 'edit'])->name('edit')
            ->where('testimonial', '[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}');
        Route::put('/{testimonial}', [TestimonialController::class, 'update'])->name('update')
            ->where('testimonial', '[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}');
        Route::delete('/{testimonial}', [TestimonialController::class, 'destroy'])->name('destroy')
            ->where('testimonial', '[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}');
    });

    Route::prefix('stats')->name('stats.')->group(function () {
        Route::get('/', [StatController::class, 'index'])->name('index');
        Route::get('/create', [StatController::class, 'create'])->name('create');
        Route::post('/', [StatController::class, 'store'])->name('store');

        // Dynamic routes should come AFTER static routes
        Route::get('/{stat}/edit', [StatController::class, 'edit'])->name('edit')
            ->where('stat', '[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}');
        Route::put('/{stat}', [StatController::class, 'update'])->name('update')
            ->where('stat', '[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}');
        Route::delete('/{stat}', [StatController::class, 'destroy'])->name('destroy')
            ->where('stat', '[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}');
    });

    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::get('/create', [ClientController::class, 'create'])->name('create');
        Route::post('/', [ClientController::class, 'store'])->name('store');

        // Dynamic routes should come AFTER static routes
        Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('edit')
            ->where('client', '[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}');
        Route::put('/{client}', [ClientController::class, 'update'])->name('update')
            ->where('client', '[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}');
        Route::delete('/{client}', [ClientController::class, 'destroy'])->name('destroy')
            ->where('client', '[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}');
    });

    Route::prefix('process-info')->name('process-info.')->group(function () {
        Route::get('/', [ProcessInfoController::class, 'index'])->name('index');
        Route::get('/create', [ProcessInfoController::class, 'create'])->name('create');
        Route::post('/', [ProcessInfoController::class, 'store'])->name('store');
        Route::get('/{processInfo}/edit', [ProcessInfoController::class, 'edit'])->name('edit')
            ->where('processInfo', '[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}');
        Route::put('/{processInfo}', [ProcessInfoController::class, 'update'])->name('update')
            ->where('processInfo', '[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}');
        Route::delete('/{processInfo}', [ProcessInfoController::class, 'destroy'])->name('destroy')
            ->where('processInfo', '[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}');
    });

    Route::prefix('audit-logs')->name('audit-logs.')->group(function () {
        Route::get('/', function() {
            $logs = [];
            return view('audit-logs.index', compact('logs'));
        })->name('index');
    });
});
