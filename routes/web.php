<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;

// Route::get('/', function () {
//      return view('welcome');
//  });


Route::get('/', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login/admin', [AuthController::class, 'login'])->name('admin.logins');


// middleware
Route::group(['middleware'=> ['auth:sanctum']], function(){


    Route::controller(DashboardController::class)->group(function () {

        Route::get('/dashboard', 'dashboard')->name('admin.dashboard');
        
    });

    Route::controller(TaskController::class)->group(function () {

        Route::get('/create/task', 'create')->name('admin.createTaskPage');
        Route::post('/create_task', 'createOfficeTask')->name('admin.create');
        Route::get('/listOfTask',  'list')->name('admin.listOfTaskPage');
        Route::get('/edit/{id}',  'edit')->name('admin.editTaskPage');
        Route::post('/update/{id}', 'update')->name('admin.update');
        Route::post('/delete/{id}',  'delete_task')->name('admin.deleteTask');
        Route::get('/supplier', 'supplier')->name('admin.supplierListPage');
        Route::get('/user', 'user')->name('admin.allUserProfile');
        Route::get('/client/list','clients')->name('admin.clientListPage');
        Route::get('/transaction', 'transaction')->name('admin.transactionListPage');
        Route::get('/qrcode', 'qrcode')->name('admin.qrcodePage');
        
        
    });


    Route::controller(FileController::class)->group(function () {

        Route::get('/upload',  'upload')->name('admin.uploadPdfPage');
        Route::post('/upload/pdf', 'upload_pdf')->name('admin.upload_file');
        Route::delete('/upload/{file}','delete_pdf')->name('admin.pdfFileDelete');

    });

    Route::controller(AuthController::class)->group(function () {

        Route::get('/logout', 'logout')->name('admin.logout');
        Route::get('/admin/profile/{id}', 'admin_profile')->name('admin.adminProfilePage');
        
    });

    
});
