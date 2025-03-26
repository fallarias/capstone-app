<?php


use App\Http\Controllers\GetRouteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RemovedorNoteneeded\FileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Auth;
// Route::get('/', function () {
//      return view('welcome');
//  });
Route::get('/test-role', function () {
    return 'Role middleware is working!';
})->middleware('role:clients');

Route::controller(AuthController::class)->group(function () {

    Route::get('/login', 'showLoginForm')->name('login')->middleware('guest');
    Route::get('/', 'showLoginForm')->middleware('guest');
    Route::post('/login/admin', 'login')->name('admin.logins');
    Route::get('/audit_login', 'audit');

});

Route::controller(ClientController::class)->group(function () {

    Route::get('/clientRegistration', 'client_registration')->name('client.registration');
    Route::post('/clientRegistration', 'client_registration_create')->name('client.registrations');

});


// Middleware
Route::group(['middleware'=> ['auth:sanctum']], function() {

    Route::group(['middleware' => function ($request, $next) {
        if (Auth::check() && Auth::user()->account_type !== 'Admin') {
            return redirect()->route('client.clientHomePage');
        }
        return $next($request);
    }], function() {

        Route::controller(DashboardController::class)->group(function () {
            Route::get('/dashboard', 'dashboard')->name('admin.dashboard');
            Route::get('/logs', 'logs')->name('admin.logsPage');
            Route::get('/dashboard/stats', 'getStats');
        });

        Route::controller(TaskController::class)->group(function () {
            Route::post('/create_task', 'createOfficeTask')->name('admin.create');
            Route::get('/edit/{id}', 'edit')->name('admin.editTaskPage');
            Route::post('/update/{id}', 'update')->name('admin.update');
            Route::post('/delete/{id}', 'delete_task')->name('admin.deleteTask');
            Route::post('/activate/{id}', 'task_activate')->name('admin.taskActivate');
            Route::post('/add/Office', 'add_office')->name('admin.addOffice');
            Route::post('/create/holiday', 'holiday')->name('admin.holidays');
        });

        Route::controller(FileController::class)->group(function () {
            Route::get('/upload', 'upload')->name('admin.uploadPdfPage');
            Route::post('/upload/pdf', 'upload_pdf')->name('admin.upload_file');
            Route::delete('/upload/{file}', 'delete_pdf')->name('admin.pdfFileDelete');
        });

        Route::controller(GetRouteController::class)->group(function () {
            Route::get('/listOfTask', 'list')->name('admin.listOfTaskPage');
            Route::get('/create/task', 'create')->name('admin.createTaskPage');
            Route::get('/user', 'user')->name('admin.allUserProfile');
            Route::get('/activated/task', 'activated_task')->name('admin.activateTaskListPage');
            Route::get('/transaction', 'transaction')->name('admin.transactionListPage');
            Route::get('/completed/transaction', 'completed_transaction')->name('admin.completedTaskListPage');
            Route::get('/audit', 'audit_trails')->name('admin.auditTrails');
            Route::get('/user/staff', 'new_staff')->name('admin.newOfficeAccount');
            Route::get('/admin/holiday', 'holiday')->name('admin.holiday');
        });

        Route::controller(AuthController::class)->group(function () {
            Route::get('/logout', 'logout')->name('admin.logout');
            Route::get('/admin/profile/{id}', 'admin_profile')->name('admin.adminProfilePage');
            Route::post('/user/accept/{id}', 'user_accept')->name('admin.accept');
            Route::post('/user/reject/{id}', 'user_reject')->name('admin.reject');
            Route::post('/user/staffs', 'new_staff')->name('admin.newOfficeAccounts');
            Route::get('/app-bar', 'app_bar')->name('components.app-bar');
        });
    });


    Route::group(['middleware' => function ($request, $next) {
        if (Auth::check() && Auth::user()->account_type !== 'client') {
            abort(403, 'Unauthorized access.');
        }
        return $next($request);
    }], function() {

        Route::controller(ClientController::class)->group(function () {
            Route::get('/logout/client', 'logout')->name('admin.logout');
            Route::get('/client/home', 'homepage')->name('client.clientHomePage');
            Route::get('/client/notification', 'notification')->name('client.clientNotification');
            Route::get('/client/notification/refresh', 'notification_refresh');
            Route::get('/client/template', 'template')->name('client.clientTemplate');
            Route::get('/client/track/{task_id}/{transaction_id}', 'track_document')->name('client.clientTrackDocument');
            Route::get('/client/transaction/', 'transaction_history')->name('client.clientTrasactionHistory');
            Route::post('/client/download/{id}', 'transaction')->name('client.clientTransaction');
            Route::get('/client/task/list/', 'task_document')->name('client.clientTaskList');
            Route::get('/client/rate_staff/{transaction_id}', 'rate')->name('client.clientRatingPage');
            Route::post('/client/review', 'review')->name('client.clientReview');
        });
    });

});


