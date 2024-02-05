<?php

use App\Http\Controllers\Auth\Socialite;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Livewire\Admin\Accounts\Role\Role;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\Auth\RegisterAdminPage;
use App\Models\RegisterToken;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::prefix('auth')->name('auth.')->group(function () {

    // Routes accessibles uniquement par les utilisateurs ayant le rôle "Admin"
    Route::middleware('guest')->group(/* ['namespace' => 'App\Livewire\Admin'], */function () {

        Route::get('login', LoginPage::class)->name('login');

        Route::get('register', RegisterPage::class)->name('register');

        Route::get('new-admin/{token}', RegisterAdminPage::class)->name('new-admin');

        Route::controller(Socialite::class)->name('socialite.')->group(function(){
            Route::get('{provider}/redirect','redirect')->name('redirect');
            Route::get('{provider}/callback','authenticate')->name('authenticate');
        });


        // Routes  pour les comptes
        /* Route::prefix('accounts')->name('accounts.')->group(function(){
            // Routes pour les roles dans les comptes
            Route::prefix('roles')->name('roles.')->group(function(){
                Route::get('/list', Roles::class)->name('index');
                Route::get('/role/{role}',Role::class)->name('show');
            });
        }); */

        /* Route::prefix('parametres')->name('parametres.')->group(function(){
            // Routes pour les roles dans les comptes
            Route::prefix('marque')->name('marque.')->group(function(){
                Route::get('/list', Marques::class)->name('index');
                Route::get('/marque/{marque}',Marque::class)->name('show');
            });
        }); */

    });


     //Route::middleware('auth')->group(/* ['namespace' => 'App\Livewire'] ,*/function () {
       // Route::get('/profile', 'ProfileController@index')->name('profile');
    //});
});

Route::prefix('auth')->middleware('guest')->group(function () {

    Volt::route('forgot-password', 'pages.auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'pages.auth.reset-password')
        ->name('password.reset');
});

Route::prefix('auth')->middleware('auth')->group(function () {
    Volt::route('verify-email', 'pages.auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

/*     Volt::route('confirm-password', 'pages.auth.confirm-password')
        ->name('password.confirm'); */
});

