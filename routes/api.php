<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

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

 Route::post('/register', [ApiController::class, 'register']);
 Route::post('/login', [ApiController::class, 'login_users']);
 Route::get('/client_file', [ApiController::class, 'client_file']);



