<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

 
 Route::controller(ApiController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    
    //Authenticated User Only
    Route::group(['middleware'=> ['auth:sanctum']], function(){
        Route::post('/logout', 'logout');
        Route::post('/transaction', 'transaction');
        Route::post('/scanned_data', 'scanned_data');
        Route::get('/template_history/{id}', 'template_history');
        Route::get('/task_document/{userId}', 'task_document');
    });
});



