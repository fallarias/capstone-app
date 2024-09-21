<?php

use App\Http\Controllers\GetRouteController;
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
        Route::get('/logs', 'logs')->name('admin.logsPage');
    });

    Route::controller(TaskController::class)->group(function () {

       
        Route::post('/create_task', 'createOfficeTask')->name('admin.create');
        Route::get('/edit/{id}',  'edit')->name('admin.editTaskPage');
        Route::post('/update/{id}', 'update')->name('admin.update');
        Route::post('/delete/{id}',  'delete_task')->name('admin.deleteTask');
        
        
        Route::post('/activate/{id}', 'task_activate')->name('admin.taskActivate');
        
        Route::post('/add/Office', 'add_office')->name('admin.addOffice');
        
    });

    //Removed or Not needed
    Route::controller(FileController::class)->group(function () {

        Route::get('/upload',  'upload')->name('admin.uploadPdfPage');
        Route::post('/upload/pdf', 'upload_pdf')->name('admin.upload_file');
        Route::delete('/upload/{file}','delete_pdf')->name('admin.pdfFileDelete');

    });
    // 
    
    Route::controller(GetRouteController::class)->group(function () {

        Route::get('/listOfTask',  'list')->name('admin.listOfTaskPage');
        Route::get('/create/task', 'create')->name('admin.createTaskPage');
        Route::get('/supplier', 'supplier')->name('admin.supplierListPage');
        Route::get('/user', 'user')->name('admin.allUserProfile');
        Route::get('/activated/task','activated_task')->name('admin.activateTaskListPage');
        Route::get('/transaction', 'transaction')->name('admin.transactionListPage');
        
    });


    Route::controller(AuthController::class)->group(function () {

        Route::get('/logout', 'logout')->name('admin.logout');
        Route::get('/admin/profile/{id}', 'admin_profile')->name('admin.adminProfilePage');
        
    });

    
});
