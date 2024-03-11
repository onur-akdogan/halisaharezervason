<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
 use App\Http\Controllers\Api\UserApiController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:api', 'prefix' => 'user'], function () {
 
    Route::get('/userGet', [UserApiController::class, 'userGet']);
    Route::get('/halisahagetAll/{id}/{addweek}', [UserApiController::class, 'halisahagetAll']);
    Route::get('/halisahadetail/{id}', [UserApiController::class, 'halisahadetail']);
    Route::post('/halisahaedit', [UserApiController::class, 'halisahaedit']);
    Route::post('/halisahaadd', [UserApiController::class, 'halisahaadd']);
    Route::post('/eventadd', [UserApiController::class, 'eventadd']);
    Route::post('/eventedit', [UserApiController::class, 'eventedit']);
    
    Route::get('/halisahadelete/{id}', [UserApiController::class, 'halisahadelete']);
    Route::get('/musteriget', [UserApiController::class, 'musteriget']);
    Route::get('/abones', [UserApiController::class, 'abones']);
    Route::get('/iptalsget', [UserApiController::class, 'iptalsget']);
    Route::get('/getallhalisaha', [UserApiController::class, 'getallhalisaha']);

    
    Route::get('/deleteback/{id}', [UserApiController::class, 'deleteback'])->name('calender.deleteback');
    Route::get('/eventdelete/{id}', [UserApiController::class, 'eventdelete'])->name('calender.eventdelete');
    
     

    Route::post('/profileupdate', [UserApiController::class, 'profileupdate']);
    Route::post('/passwordupdate', [UserApiController::class, 'passwordupdate']);
    Route::get('/deleteaccound', [UserApiController::class, 'deleteaccound']);

    Route::get('/getbanks', [UserApiController::class, 'getbanks']);
    Route::get('/downloadImages/{id}', [UserApiController::class, 'getbanks']);
   

});


 Route::post('/login', [UserApiController::class, 'login']);
 Route::post('/register', [UserApiController::class, 'register']);
