<?php

use App\Http\Controllers\Admin\CmsItemController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MediaAssetController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PublicContentController;
use App\Http\Controllers\PublicSiteController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

Route::get('/', PublicSiteController::class)->name('home');

Route::get('/la-ciudad', PublicSiteController::class)->name('city');
Route::get('/mision-vision', PublicSiteController::class)->name('mission-vision');
Route::get('/servicios', PublicSiteController::class)->name('services');
Route::get('/noticias', PublicSiteController::class)->name('news');
Route::get('/transparencia', fn () => Redirect::route('accountability'))->name('transparency');
Route::get('/transparencia/rendicion-de-cuentas', PublicSiteController::class)->name('accountability');
Route::get('/transparencia/lotaip', PublicSiteController::class)->name('lotaip');
Route::get('/contacto', PublicSiteController::class)->name('contact');
Route::get('/publicacion/{cmsItem}', [PublicContentController::class, 'show'])->name('public.content.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::get('/contenido', [CmsItemController::class, 'index'])->name('content.index');
        Route::post('/contenido', [CmsItemController::class, 'store'])->name('content.store');
        Route::put('/contenido/{cmsItem}', [CmsItemController::class, 'update'])->name('content.update');
        Route::delete('/contenido/{cmsItem}', [CmsItemController::class, 'destroy'])->name('content.destroy');
        Route::get('/modulos/{module}', [CmsItemController::class, 'module'])->name('modules.show');
        Route::post('/modulos/{module}', [CmsItemController::class, 'storeForModule'])->name('modules.store');

        Route::get('/media', [MediaAssetController::class, 'index'])->name('media.index');
        Route::post('/media', [MediaAssetController::class, 'store'])->name('media.store');
        Route::delete('/media/{mediaAsset}', [MediaAssetController::class, 'destroy'])->name('media.destroy');

        Route::middleware('role:administrador')->group(function () {
            Route::get('/usuarios', [UserController::class, 'index'])->name('users.index');
            Route::post('/usuarios', [UserController::class, 'store'])->name('users.store');
            Route::put('/usuarios/{user}', [UserController::class, 'update'])->name('users.update');
        });
    });
