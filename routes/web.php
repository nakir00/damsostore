<?php

use App\Livewire\Admin\Accounts\Accounts;
use App\Livewire\Admin\Accounts\DeleteUser;
use App\Livewire\Admin\Accounts\Role\Role;
use App\Livewire\Admin\Accounts\Role\Roles;
use App\Livewire\Admin\Accounts\UpdatePassword;
use App\Livewire\Admin\Accounts\UpdateProfile;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Parametres\Marque\Marque;
use App\Livewire\Admin\Parametres\Marque\Marques;
use App\Livewire\Guest\Collection\CollectionPage;
use App\Livewire\Guest\Product\ProductPage;
use App\Livewire\Guest\Search\Search;
use App\Livewire\Guest\Welcome\WelcomePage;

use App\Models\Collection;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Facades\Invoice;
use Livewire\Volt\Volt;

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





/*
Route::prefix('client')->name('client.')->group(function () {
    // Routes accessibles uniquement par les utilisateurs ayant le rôle "Admin"
    Route::middleware(['auth', 'role:admin'])->group(['namespace' => 'App\Livewire'],function () {
        Route::get('/dashboard', )->name('dashboard');
        //Route::get('/roles', '')->name('users');
    });

    // Routes accessibles par tous les utilisateurs authentifiés
    Route::middleware('auth')->group(['namespace' => 'App\Livewire'],function () {
        Route::get('/profile', 'ProfileController@index')->name('profile');
    });
});

Route::prefix('admin')->name('user.')->group(function () {
    // Routes accessibles uniquement par les utilisateurs ayant le rôle "User"
    Route::middleware(['auth', 'role:user'])->group(['namespace' => 'App\Livewire'],function () {
        Route::get('/dashboard', 'UserController@dashboard')->name('dashboard');
    });

    // Routes accessibles par tous les utilisateurs authentifiés
    Route::middleware('auth')->group(['namespace' => 'App\Livewire'],function () {
        Route::get('/profile', 'ProfileController@index')->name('profile');
    });
}); */


/* Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');*/
/* Route::get('pdf',function(){
    $order=Order::where('id',21)->with(['Address','orderables','orderables.orderable'])->get()->first();
                $client = new Party([
                    'name'          => "{$order->Address->first_name} {$order->Address->last_name}",
                    'custom_fields' => [
                        'e-mail'        => "{$order->Address->contact_email}",
                        'numéro de téléphone' => "{$order->Address->contact_phone}",
                    ],
                ]);

                $Adresse = new Party([
                    'name'          => "{$order->Address->pays} ,{$order->Address->region}",
                    'custom_fields' => [
                        'Département' => "{$order->Address->departement}",
                        'Commune' => "{$order->Address->commune}",
                        'details 1'=>"{$order->Address->line_one}",
                        'details 2'=>"{$order->Address->line_two}",
                        'details 3'=>"{$order->Address->line_three}",
                    ],
                ]);

                $items=[];
                foreach ($order->orderables as $line) {
                    $option=json_decode($line->option);
                    $objet=InvoiceItem::make($line->orderable->name);
                    $objet->description(" $option->name  : $option->value");
                    $objet->quantity($line->quantity);
                    $objet->pricePerUnit($line->unit_price);
                    $objet->discount($line->discount_total);
                    $objet->subTotalPrice($line->total);
                    $items[]=$objet;
                }


                $invoice = Invoice::make('Facture')
                    ->serialNumberFormat('{SEQUENCE}/{SERIES}')
                    ->seller($Adresse)
                    ->buyer($client)
                    ->date(now())
                    ->dateFormat('d/m/Y')
                    ->payUntilDays(14)
                    ->currencySymbol('F')
                    ->currencyCode('francs cfa')
                    ->currencyFormat('{VALUE}{SYMBOL}')
                    ->currencyThousandsSeparator('.')
                    ->currencyDecimalPoint(',')
                    ->filename('facture_'.$order->reference)
                    ->addItems($items)
                    // You can additionally save generated invoice to configured disk
                    ->save('public');

                    $link = $invoice->url();
                    return $invoice->stream();

    return $invoice->stream();
}); */

Route::get('/redirect', function(){
return redirect(route('auth.login'));
})->name('login');


Route::prefix('')->name('')->group(function () {
    Route::get('/', WelcomePage::class)->name('home');
    Route::get('/collection/{slug}', CollectionPage::class)->name('collection');
    Route::get('/product/{slug}',ProductPage::class)->name("product");
    Volt::route('checkout', 'guest.cart.checkout')->name('checkout');
    Route::get('/search', Search::class)->name("search");
});


Route::prefix('client')->name('client.')->group(function () {



    // Routes accessibles uniquement par les utilisateurs ayant le rôle "Admin"
    Route::middleware(['auth', 'verified'])->group(/* ['namespace' => 'App\Livewire\Admin'], */function () {

        Route::get('/dashboard', Dashboard::class)->name('dashboard');

        // Routes  pour les comptes
        Route::get('/accounts', Accounts::class)->name('accounts');

        Route::prefix('parametres')->name('parametres.')->group(function(){
            // Routes pour les roles dans les comptes
            Route::prefix('marque')->name('marque.')->group(function(){
                Route::get('/list', Marques::class)->name('index');
                Route::get('/marque/{marque}',Marque::class)->name('show');
            });
        });

    });


     //Route::middleware('auth')->group(/* ['namespace' => 'App\Livewire'] ,*/function () {
       // Route::get('/profile', 'ProfileController@index')->name('profile');
    //});
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

/* Route::view('userManagement', 'userManagement')
    ->middleware(['auth'])
    ->name('roles'); */

require __DIR__.'/auth.php';
