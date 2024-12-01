<?php

use App\Http\Controllers\StaffApiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientApiController;
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

 
 Route::controller(StaffApiController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    //Route::get('vue','vue');
    
    //Authenticated User Only
    Route::group(['middleware'=> ['auth:sanctum']], function(){

        Route::post('/logout', 'logout');
        Route::post('/scanned_data/{department}', 'scanned_data');
        Route::get('/staff_notification', 'staff_notification');
        Route::post('/lack_requirement/{id}/{department}', 'lack_Requirements');
        Route::post('/resume_transaction/{id}/{department}', 'resume_transaction');
        Route::get('/check_resume_transaction/{id}/{department}', 'check_resume_transaction');
        Route::post('/finish_transaction/{transaction_id}/{department}/{audit_id}', 'finish_transaction');
        Route::get('/staff_chart/{userId}', 'staff_chart');
        Route::post('/message_office/{department}', 'message_office');
        Route::get('/all_office/{department}', 'all_office');
        Route::get('/staff_scanned_history/{department}', 'staff_scanned_history');
    });
});

Route::controller(ClientApiController::class)->group(function () {

    //Authenticated User Only
    Route::group(['middleware'=> ['auth:sanctum']], function(){
        //Route::post('/logout', 'logout');
        Route::post('/transaction', 'transaction');
        //Route::post('/scanned_data/{department}', 'scanned_data');
        Route::get('/notifications/{user}', 'notification');
        Route::get('/template_history/{id}/{user_id}', 'template_history');
        Route::get('/task_document/{userId}', 'task_document');
        Route::get('/client_file', 'client_file');
        //Route::get('/staff_notification', 'staff_notification');
        //Route::post('/lack_requirement/{id}/{department}', 'lack_Requirements');
        //Route::post('/resume_transaction/{id}/{department}', 'resume_transaction');
        Route::get('/client_chart/{userId}', 'client_chart');
    });
});


