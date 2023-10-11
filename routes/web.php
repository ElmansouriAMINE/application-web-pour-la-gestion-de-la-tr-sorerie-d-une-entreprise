<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AchatController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BancaireController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\ClientController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Profile Routes
Route::prefix('profile')->name('profile.')->middleware('auth')->group(function(){
    Route::get('/', [HomeController::class, 'getProfile'])->name('detail');
    Route::post('/update', [HomeController::class, 'updateProfile'])->name('update');
    Route::post('/change-password', [HomeController::class, 'changePassword'])->name('change-password');
});

// Roles
Route::resource('roles', App\Http\Controllers\RolesController::class);

// Permissions
Route::resource('permissions', App\Http\Controllers\PermissionsController::class);

// Users 
Route::middleware('auth')->prefix('users')->name('users.')->group(function(){
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/store', [UserController::class, 'store'])->name('store');
    Route::get('/edit/{user}', [UserController::class, 'edit'])->name('edit');
    Route::put('/update/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/delete/{user}', [UserController::class, 'delete'])->name('destroy');
    Route::get('/update/status/{user_id}/{status}', [UserController::class, 'updateStatus'])->name('status');

    
    Route::get('/import-users', [UserController::class, 'importUsers'])->name('import');
    Route::post('/upload-users', [UserController::class, 'uploadUsers'])->name('upload');

    Route::get('export/', [UserController::class, 'export'])->name('export');

});




//Achats


Route::middleware('auth')->prefix('achats')->name('achats.')->group(function(){
    Route::get('/', [AchatController::class, 'index'])->name('index');
    Route::get('/create', [AchatController::class, 'create'])->name('create');
    Route::post('/store', [AchatController::class, 'store'])->name('store');
    Route::get('/edit/{achat}', [AchatController::class, 'edit'])->name('edit');
    Route::put('/update/{achat}', [AchatController::class, 'update'])->name('update');
   

    Route::get('/import-achats', [AchatController::class, 'importAchats'])->name('import');
    Route::post('/upload-achats', [AchatController::class, 'uploadAchats'])->name('upload');

    Route::get('export/', [AchatController::class, 'export'])->name('export');
    Route::get('charts/', [AchatController::class, 'charts'])->name('charts');
    Route::get('charts/data', [AchatController::class, 'achatsChart'])->name('achatsChart');
    Route::get('/update/status/{achat_id}/{status}', [AchatController::class, 'updateStatus'])->name('status');
    Route::get('table/data1', [AchatController::class, 'achatsTable1'])->name('achatsTable1');
    Route::get('table/data2', [AchatController::class, 'achatsTable2'])->name('achatsTable2');

});





//Reglement Bancaire


Route::middleware('auth')->prefix('bancaires')->name('bancaires.')->group(function(){
    Route::get('/', [BancaireController::class, 'index'])->name('index');
    Route::get('/create', [BancaireController::class, 'create'])->name('create');
    Route::post('/store', [BancaireController::class, 'store'])->name('store');
    Route::get('/edit/{bancaire}', [BancaireController::class, 'edit'])->name('edit');
    Route::put('/update/{bancaire}', [BancaireController::class, 'update'])->name('update');
    

    Route::get('/import-bancaires', [BancaireController::class, 'importBancaires'])->name('import');
    Route::post('/upload-bancaires', [BancaireController::class, 'uploadBancaires'])->name('upload');

    Route::get('export/', [BancaireController::class, 'export'])->name('export');
    Route::get('charts/', [BancaireController::class, 'charts'])->name('charts');
    Route::get('charts/data', [BancaireController::class, 'bancairesChart'])->name('bancairesChart');
    Route::get('/update/status/{bancaire_id}/{status}', [BancaireController::class, 'updateStatus'])->name('status');
    Route::get('table/regbancaire/data/1', [BancaireController::class, 'bancairesTable1'])->name('bancairesTable1');
    Route::get('table/regbancaire/data/2', [BancaireController::class, 'bancairesTable2'])->name('bancairesTable2');
    

});



//Routes Fournisseurs


Route::middleware('auth')->prefix('fournisseurs')->name('fournisseurs.')->group(function(){
    Route::get('/', [FournisseurController::class, 'index'])->name('index');
    Route::get('/create', [FournisseurController::class, 'create'])->name('create');
    Route::post('/store', [FournisseurController::class, 'store'])->name('store');
    Route::get('/edit/{fournisseur}', [FournisseurController::class, 'edit'])->name('edit');
    Route::put('/update/{fournisseur}', [FournisseurController::class, 'update'])->name('update');
    Route::delete('/delete/{fournisseur}', [FournisseurController::class, 'delete'])->name('destroy');

    Route::get('/import-fournisseurs', [FournisseurController::class, 'importFournisseurs'])->name('import');
    Route::post('/upload-fournisseurs', [FournisseurController::class, 'uploadFournisseurs'])->name('upload');

    Route::get('export/', [FournisseurController::class, 'export'])->name('export');
   

});



//Routes Clients


Route::middleware('auth')->prefix('clients')->name('clients.')->group(function(){
    Route::get('/', [ClientController::class, 'index'])->name('index');
    Route::get('/create', [ClientController::class, 'create'])->name('create');
    Route::post('/store', [ClientController::class, 'store'])->name('store');
    Route::get('/edit/{client}', [ClientController::class, 'edit'])->name('edit');
    Route::put('/update/{client}', [ClientController::class, 'update'])->name('update');
    Route::delete('/delete/{client}', [ClientController::class, 'delete'])->name('destroy');

    Route::get('/import-clients', [ClientController::class, 'importClients'])->name('import');
    Route::post('/upload-clients', [ClientController::class, 'uploadClients'])->name('upload');

    Route::get('export/', [ClientController::class, 'export'])->name('export');
   

});

