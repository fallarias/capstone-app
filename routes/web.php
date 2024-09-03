<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\signupController;
use App\Http\Controllers\DashController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\AuthController;
use App\Models\File;

Route::get('/', function () {
     return view('welcome');
 });



Route::get('/signup', [signupController::class, 'index'])->name('admin.signup');
Route::post('/signup', [signupController::class, 'submit'])->name('admin.signups');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/login', [AuthController::class, 'login'])->name('admin.logins');
// middleware
Route::get('/dashboard', [DashController::class, 'task'])->name('admin.dashboard');
Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout');
Route::get('/create', [DashController::class, 'create'])->name('admin.createtask');
Route::post('/create', [DashController::class, 'createtask'])->name('admin.create');
Route::get('/list', [DashController::class, 'list'])->name('admin.listtask');
Route::get('/edit/{id}', [DashController::class, 'edit'])->name('admin.edit');
Route::post('/update/{id}', [DashController::class, 'update'])->name('admin.update');
Route::post('/delete/{id}', [DashController::class, 'delete'])->name('admin.delete');
Route::get('/supplier', [DashController::class, 'supplier'])->name('admin.supplier');
Route::get('/user', [DashController::class, 'user'])->name('admin.user');
Route::get('/clients', [DashController::class, 'clients'])->name('admin.clients');
Route::get('/transaction', [DashController::class, 'transaction'])->name('admin.transaction');
Route::get('/qrcode', [DashController::class, 'qrcode'])->name('admin.qrcode');
Route::get('/request', [DashController::class, 'request'])->name('admin.request');

Route::get('/person/{id}', [AuthController::class, 'person'])->name('admin.person');

Route::get('/upload', [FileController::class, 'upload'])->name('admin.upload');
Route::get('/uploaded_files', [FileController::class, 'uploaded_files'])->name('admin.uploaded_files');
Route::post('/upload', [FileController::class, 'upload_files'])->name('admin.upload_file');
//testing
Route::get('/document/{id}', [FileController::class, 'showDocument'])->name('document.show');

// Route::group(['middleware'=> ['auth:sanctum']], function(){
// });