<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadController;

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

Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::group(['prefix' => 'leads', 'as' => 'leads.'], function(){
    Route::controller(LeadController::class)->group(function(){
        Route::get('/', 'index')->name('index');
        Route::post('/import-leads', 'import')->name('import');
        Route::get('/export-leads', 'export')->name('export');
    });
});
