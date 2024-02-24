<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CalenderController;
use App\Http\Controllers\HalisahaController;


use App\Http\Controllers\ProfileController;
 
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

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('optimize:clear');
 
    return "Cache cleared successfully";
 });


Route::middleware('auth')->group(function () {
 
Route::get('/', [HalisahaController::class, 'index'])->name('home');

Route::get('users', [UserController::class, 'index'])->name('users.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/sms', [CalenderController::class, 'sms'])->name('profile.sms');
 

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/calenderindex/{id}', [CalenderController::class, 'index'])->name('calender.index');
    Route::get('/apicalender/{id}/{addweek}', [CalenderController::class, 'apicalender'])->name('calender.apicalender');

    Route::post('/calender/add', [CalenderController::class, 'add'])->name('calender.add');
    Route::post('/calender/update', [CalenderController::class, 'update'])->name('calender.update');

  
    Route::get('/calender/{id}', [CalenderController::class, 'delete'])->name('calender.delete');

    Route::get('/halisahaindex', [HalisahaController::class, 'index'])->name('halisaha.index');
    Route::get('/allindex', [HalisahaController::class, 'allindex'])->name('halisaha.allindex');

    
    Route::get('/halisahaaddpage', [HalisahaController::class, 'addpage'])->name('halisaha.addpage');
    Route::post('/halisahaadd', [HalisahaController::class, 'add'])->name('halisaha.add');
    Route::get('/halisahadelete/{id}', [HalisahaController::class, 'delete'])->name('halisaha.delete');
    Route::get('/halisahaeditpage/{id}', [HalisahaController::class, 'editpage'])->name('halisaha.editpage');
    Route::post('/halisahaupdate', [HalisahaController::class, 'update'])->name('halisaha.update');

    Route::get('/admin/users', [UserController::class, 'users'])->name('admin.users');
    Route::get('/user/musteriler', [UserController::class, 'musteriler'])->name('user.musteriler');
    Route::get('/user/musterileriptal', [UserController::class, 'musterileriptal'])->name('user.musterileriptal');
    Route::get('/user/aboneler', [UserController::class, 'aboneler'])->name('user.aboneler');

    


});

require __DIR__.'/auth.php';
