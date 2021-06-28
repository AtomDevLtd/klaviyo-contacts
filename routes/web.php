<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContactListController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrackActivityController;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

require __DIR__.'/auth.php';

Route::group([
    'middleware' => ['auth'],
], function () {

    Route::resource('contactLists', ContactListController::class);
    Route::put('/contactLists/sync/{contactList}', [ContactListController::class, 'syncWithKlaviyo']);

    Route::resource('contactLists.contacts', ContactController::class);
    Route::post('/contactLists/{contactList}/contactsImport', [ContactController::class, 'import'])->name('contacts-import');
    Route::put('/contacts/sync/{contact}', [ContactController::class, 'syncWithKlaviyo']);

    Route::view('profile', 'pages.profile.edit')->name('profile');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::post('trackActivity', TrackActivityController::class)->name('trackActivity');

});

