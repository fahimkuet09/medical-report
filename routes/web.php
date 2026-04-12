<?php

use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReportRemarksController;
use App\Http\Controllers\Admin\ReportSyncController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::post('/api/reports/refresh', [ReportSyncController::class, 'refresh'])->name('api.reports.refresh');

    Route::prefix('admin')->name('admin.')->group(function (): void {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
        Route::get('/reports/{report}/pdf', [ReportController::class, 'pdf'])->name('reports.pdf');
        Route::patch('/reports/{report}/remarks', [ReportRemarksController::class, 'update'])->name('reports.remarks.update');
    });
});
