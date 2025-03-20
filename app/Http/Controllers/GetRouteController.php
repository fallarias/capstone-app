<?php

namespace App\Http\Controllers;

use App\Events\AdminGetCreate;
use App\Events\AdminGetList;
use App\Events\AdminGetTransaction;
use App\Events\AdminGetComplted;
use App\Events\AdminGetHoliday;
use App\Events\AdminGetTrails;
use App\Models\Audit;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Transaction;
use App\Models\NewOffice;
use App\Models\Task;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Exception;
use Carbon\Carbon;
use App\Models\Holiday;

class GetRouteController extends Controller
{
    public function create(){
        
        $offices = User::select('department')
                        ->where('account_type','office staff')
                        ->distinct()
                        ->get();
        $name = Task::all();
         //app-bar
        $admin = User::select('firstname','lastname','middlename','user_id')->where('account_type','Admin')->first();
        $UserId = session('user_id');
        $user = User::find($UserId);
        event(new AdminGetCreate($user));
        return view('admin.createTaskPage', compact('offices', 'name','admin'));

    }

    public function list() {
        // Get tasks with their associated files
        $data = Task::with('files')->get();

        // Iterate through the files and prepare URLs for PDFs
        foreach ($data as $task) {
            foreach ($task->files as $file) {
                if ($file->type == 'application/pdf') {
                    $file->pdfUrl = Storage::url($file->filepath); // Generate URL for the PDF file

                    // Remove the .pdf extension from the file name
                    $file->filename = pathinfo($file->filename, PATHINFO_FILENAME);

                } else {
                    $file->pdfUrl = null;
                }
            }
        }
        //app-bar
        $admin = User::select('firstname','lastname','middlename','user_id')->where('account_type','Admin')->first();
        $UserId = session('user_id');
        $user = User::find($UserId);
        event(new AdminGetList($user));

        return view('admin.listOfTaskPage', compact('data','admin'));
    }

    public function activated_task(){
        // Get tasks with their associated files
        $data = Task::with('files')->where('soft_del', '0')->where('status','=',1)->get();

        // Iterate through the files and prepare URLs for PDFs
        foreach ($data as $task) {
            foreach ($task->files as $file) {
                if ($file->type == 'application/pdf') {
                    $file->pdfUrl = Storage::url($file->filepath); // Generate URL for the PDF file
                } else {
                    $file->pdfUrl = null;
                }
            }
        }
        //app-bar
        $admin = User::select('firstname','lastname','middlename')->where('account_type','Admin')->first();

        return view('admin.activatedTaskListPage', compact('data','admin'));

    }

    public function transaction(){

        $transactions = Transaction::with('user')->get();
        //app-bar
        $admin = User::select('firstname','lastname','middlename','user_id')->where('account_type','Admin')->first();
        $UserId = session('user_id');
        $user = User::find($UserId);
        event(new AdminGetTransaction($user));
        return view('admin.transactionListPage', compact('transactions','admin'));

    }

    public function completed_transaction(){

        $transactions = Transaction::with('user','task')->where('status', 'finished')->get();
        //app-bar
        $admin = User::select('firstname','lastname','middlename','user_id')->where('account_type','Admin')->first();
        $UserId = session('user_id');
        $user = User::find($UserId);
        event(new AdminGetComplted($user));
        return view('admin.completedTaskListPage', compact('transactions','admin'));

    }

    public function holiday(){

        $currentYear = Carbon::now()->year;

        // Fetch holidays for the current year
        $holidays = Holiday::whereYear('holiday_date', $currentYear)->get();
        //$this->populateHolidays(2024, 2027);
        //app-bar
        
        $admin = User::select('firstname','lastname','middlename','user_id')->where('account_type','Admin')->first();
        $UserId = session('user_id');
        $user = User::find($UserId);
        event(new AdminGetHoliday($user));
        return view('admin.createHoliday', compact('admin','holidays'));

    }

    function populateHolidays($startYear, $endYear)
    {
        $holidays = [
            // New Year's Day
            ['month' => 1, 'day' => 1, 'description' => 'New Year\'s Day'],
        
            // Chinese New Year (varies yearly)
            ['month' => 1, 'day' => 22, 'description' => 'Chinese New Year (Placeholder Example)'],
        
            // Valentine’s Day
            ['month' => 2, 'day' => 14, 'description' => 'Valentine\'s Day'],
        
            // EDSA People Power Revolution Anniversary
            ['month' => 2, 'day' => 25, 'description' => 'EDSA People Power Revolution Anniversary'],
        
            // Araw ng Kagitingan (Day of Valor)
            ['month' => 4, 'day' => 9, 'description' => 'Araw ng Kagitingan (Day of Valor)'],
        
            // Good Friday (placeholder)
            ['month' => 4, 'day' => 7, 'description' => 'Good Friday (Placeholder Example)'],
        
            // Black Saturday (placeholder)
            ['month' => 4, 'day' => 8, 'description' => 'Black Saturday (Placeholder Example)'],
        
            // Labor Day
            ['month' => 5, 'day' => 1, 'description' => 'Labor Day'],
        
            // Independence Day
            ['month' => 6, 'day' => 12, 'description' => 'Independence Day'],
        
            // Ninoy Aquino Day
            ['month' => 8, 'day' => 21, 'description' => 'Ninoy Aquino Day'],
        
            // National Heroes Day (last Monday of August, placeholder)
            ['month' => 8, 'day' => 28, 'description' => 'National Heroes Day (Placeholder Example)'],
        
            // All Saints' Day
            ['month' => 11, 'day' => 1, 'description' => 'All Saints\' Day'],
        
            // All Souls’ Day
            ['month' => 11, 'day' => 2, 'description' => 'All Souls\' Day'],
        
            // Bonifacio Day
            ['month' => 11, 'day' => 30, 'description' => 'Bonifacio Day'],
        
            // Christmas Eve
            ['month' => 12, 'day' => 24, 'description' => 'Christmas Eve'],
        
            // Christmas Day
            ['month' => 12, 'day' => 25, 'description' => 'Christmas Day'],
        
            // Rizal Day
            ['month' => 12, 'day' => 30, 'description' => 'Rizal Day'],
        
            // New Year's Eve
            ['month' => 12, 'day' => 31, 'description' => 'New Year\'s Eve'],
        
            // Special (Non-Working) Holidays in the Philippines
            ['month' => 8, 'day' => 6, 'description' => 'National Indigenous Peoples Day (Placeholder Example)'],
            ['month' => 3, 'day' => 8, 'description' => 'International Women\'s Day (Placeholder Example)'],
        ];
        

        foreach (range($startYear, $endYear) as $year) {
            foreach ($holidays as $holiday) {
                $holidayDate = Carbon::createFromDate($year, $holiday['month'], $holiday['day']);
                
                // Check if the holiday already exists to avoid duplicates
                if (!Holiday::whereDate('holiday_date', $holidayDate->toDateString())->exists()) {
                    Holiday::create([
                        'holiday_date' => $holidayDate,
                        'description' => $holiday['description'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    public function user(){
        $user = User::whereIn('account_type', ['client', 'office staff', 'supplier'])->get();
        //app-bar
        $admin = User::select('firstname','lastname','middlename')->where('account_type','Admin')->first();
        return view('admin.allUserProfile', compact('user','admin'));
    }

    public function audit_trails(Request $request)
    {
        // Fetch transactions that are ongoing and have a deadline in the future
        $audit = Audit::all()->whereNotNull('start');
        $email = Audit::where('email_reminder_sent', false)
                        ->whereNull('finished')->get();

        Log::info('Found ' . $audit->count() . ' transactions to process.');

        // Check if there are transactions to process
        if ($email->isEmpty()) {
            Log::info('No ongoing transactions with upcoming deadlines found.');
        }

        // Process each transaction and send email reminders if the request is an AJAX call
        if ($request->ajax()) {
            foreach ($email as $audits) {
                // Fetch admin user only once
                $email_user = User::where('department', $audits->office_name)
                                    ->where('account_type', 'office staff')->first();

                // Check if an admin user exists
                if (!$email_user) {
                    Log::warning('No admin user found to send a deadline reminder email.');
                    continue; // Continue processing the other transactions
                }

                // Check if the transaction deadline is within the next 60 minutes
                $minutesToDeadline = now()->diffInMinutes($audits->deadline, false);

                if ($minutesToDeadline <= 60 && $minutesToDeadline > 0) {
                    try {
                        // Send the email
                        Mail::send('admin.deadline', ['transaction' => $audits], function ($message) use ($email_user) {
                            $message->to($email_user->email);
                            $message->subject('Reminder: Transaction Deadline Approaching');
                        });

                        // Mark as reminder sent
                        $audits->email_reminder_sent = true;
                        $audits->save();
                        // Log success message
                        Log::info('Reminder email sent to ' . $email_user->email . ' for transaction ID ' . $audits->id . '.');
                    } catch (Exception $e) {
                        // Log if there's an error during the email sending process
                        Log::error('Failed to send email for transaction ID ' . $audits->id . '. Error: ' . $e->getMessage());
                    }
                }
            }

            // Return the JSON response with transaction count
            return response()->json([
                'transaction_count' => $audit->count(),
                'transactions' => $audit, // Pass the transactions or count if needed
            ]);
        }

        //app-bar
        $admin = User::select('firstname','lastname','middlename','user_id')->where('account_type','Admin')->first();
        $UserId = session('user_id');
        $user = User::find($UserId);
        event(new AdminGetTrails($user));
        // Return the view with the transaction data for a regular request
        return view('admin.auditTrails', ['transaction' => $audit,'admin'=>$admin]);
    }

    public function new_staff(){
    //app-bar
        $admin = User::select('firstname','lastname','middlename')->where('account_type','Admin')->first();
        return view('admin.newOfficeAccount','admin');
    }
}    
