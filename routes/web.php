<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\EvidenceController;
use App\Http\Controllers\UnitExpedientPdfController;
use App\Http\Controllers\ShippingGuidePdfController;
use App\Models\UnitTransferAssignment;
use App\Http\Controllers\FuelTicketController;
use App\Http\Controllers\DashboardController;

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

        Route::get(
            '/dashboard',
            DashboardController::class
        )
            ->name('dashboard');

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

        Route::middleware('can:arrival.view')
            ->group(function () {

                Route::view(
                    '/arrivals',
                    'operations.arrivals'
                )->name('operations.arrivals');
            });


        Route::middleware('can:assembly.view')
            ->group(function () {

                Route::view(
                    '/assemblies',
                    'operations.assemblies'
                )->name('operations.assemblies');
            });


        Route::middleware('can:delivery.view')
            ->group(function () {

                Route::view(
                    '/deliveries',
                    'operations.deliveries'
                )->name('operations.deliveries');
            });

        Route::get(
            '/evidences/{evidence}',
            [EvidenceController::class, 'show']
        )->name('evidences.show');


        Route::get(
            '/evidences/{evidence}/download',
            [EvidenceController::class, 'download']
        )->name('evidences.download');

        Route::view(
            '/admin/users',
            'admin.users'
        )
            ->middleware('can:users.manage')
            ->name('admin.users');

        Route::get(
            '/units/{unit}/expedient/pdf',
            [
                UnitExpedientPdfController::class,
                'download',
            ]
        )
            ->middleware([
                'can:units.view',
                'can:evidences.view',
            ])
            ->name(
                'units.expedient.pdf'
            );

        Route::view(
            '/reports/assembly',
            'reports.assembly'
        )
            ->middleware(
                'can:reports.view'
            )
            ->name(
                'reports.assembly'
            );

        Route::get(
            '/units/{unit}/shipping-guide/pdf',
            [
                ShippingGuidePdfController::class,
                'download',
            ]
        )
            ->middleware([
                'can:units.view',
            ])
            ->name(
                'units.shipping-guide.pdf'
            );

        Route::view(
            '/my-transfers',
            'transfers.my-transfers'
        )
            ->middleware([
                'auth',
                'can:transfers.view',
            ])
            ->name(
                'transfers.mine'
            );

        Route::get(
            '/my-transfers/{assignment}/fuel',
            function (UnitTransferAssignment $assignment) {

                return view(
                    'fuel.create',
                    [
                        'assignment' =>
                            $assignment,
                    ]
                );
            }
        )
            ->middleware([
                'auth',
                'can:fuel.create',
            ])
            ->name(
                'fuel.create'
            );

        Route::get(
            '/fuel-loads/{fuelLoad}/ticket',
            FuelTicketController::class
        )
            ->middleware([
                'auth',
                'can:fuel.view',
            ])
            ->name(
                'fuel.ticket'
            );
    });
