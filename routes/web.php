<?php

use App\Livewire\Admin\Accounts\Role\Role;
use App\Livewire\Admin\Accounts\Role\Roles;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Parametres\Marque\Marque;
use App\Livewire\Admin\Parametres\Marque\Marques;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'welcome');



/* Route::prefix('admin')->name('admin.')->group(function () { */
    // Routes accessibles uniquement par les utilisateurs ayant le rôle "Admin"
    /* Route::middleware(['auth', 'role:admin'])->group(['namespace' => 'App\Livewire'],function () {
        Route::get('/dashboard', '')->name('dashboard');
        Route::get('/roles', '')->name('users');
    }); */

    // Routes accessibles par tous les utilisateurs authentifiés
/*     Route::middleware('auth')->group(['namespace' => 'App\Livewire'],function () {
        Route::get('/profile', 'ProfileController@index')->name('profile');
    });
});

Route::prefix('admin')->name('user.')->group(function () { */
    // Routes accessibles uniquement par les utilisateurs ayant le rôle "User"
   /*  Route::middleware(['auth', 'role:user'])->group(['namespace' => 'App\Livewire'],function () {
        Route::get('/dashboard', 'UserController@dashboard')->name('dashboard');
    }); */

    // Routes accessibles par tous les utilisateurs authentifiés
/*     Route::middleware('auth')->group(['namespace' => 'App\Livewire'],function () {
        Route::get('/profile', 'ProfileController@index')->name('profile');
    });
}); */


/* Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');



Route::prefix('admin')->name('admin.')->group(function () {*/

    // Routes accessibles uniquement par les utilisateurs ayant le rôle "Admin"
   // Route::middleware(['auth'/* , 'role:admin' */])->group(/* ['namespace' => 'App\Livewire\Admin'], */function () {

    //    Route::get('/dashboard', Dashboard::class)->name('dashboard');

        // Routes  pour les comptes
     //   Route::prefix('accounts')->name('accounts.')->group(function(){
            // Routes pour les roles dans les comptes
      //      Route::prefix('roles')->name('roles.')->group(function(){
      //          Route::get('/list', Roles::class)->name('index');
       //         Route::get('/role/{role}',Role::class)->name('show');
       //     });
       // });

       // Route::prefix('parametres')->name('parametres.')->group(function(){
            // Routes pour les roles dans les comptes
            //Route::prefix('marque')->name('marque.')->group(function(){
                //Route::get('/list', Marques::class)->name('index');
                //Route::get('/marque/{marque}',Marque::class)->name('show');
            //});
        //});

   // });


    // Routes accessibles par tous les utilisateurs authentifiés
     //Route::middleware('auth')->group(/* ['namespace' => 'App\Livewire'] ,*/function () {
        //Route::get('/profile', 'ProfileController@index')->name('profile');
    //});
//});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

/* Route::view('userManagement', 'userManagement')
    ->middleware(['auth'])
    ->name('roles'); */

require __DIR__.'/auth.php';
