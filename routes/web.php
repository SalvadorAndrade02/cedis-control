<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\UnitController;

Route::middleware('guest')
    ->group(function () {
        Route::get(
            '/login',
            [LoginController::class, 'create']
        )->name('login');

        Route::post(
            '/login',
            [LoginController::class, 'store']
        )->name('login.store');
    });

Route::middleware('auth')
    ->group(function () {
        Route::get(
            '/',
            function () {
                return redirect()
                    ->route('dashboard');
            }
        );

        Route::view(
            '/dashboard',
            'dashboard'
        )->name('dashboard');

        Route::post(
            '/logout',
            [LoginController::class, 'destroy']
        )->name('logout');

        Route::view(
            '/imports',
            'imports.index'
        )->name('imports.index');

        Route::view(
            '/units',
            'units.index'
        )->name('units.index');

        Route::get(
            '/units/{unit}',
            [UnitController::class, 'show']
        )->name('units.show');

        Route::get(
            '/documents/{document}/download',
            [DocumentController::class, 'download']
        )->name('documents.download');
    });
