<?php

namespace App\Http\Controllers;

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

class GetRouteController extends Controller
{
    public function create(){
        
        $offices = User::select('department')
                        ->where('account_type','office staff')
                        ->distinct()
                        ->get();
        $name = Task::all();
         //app-bar
         $admin = User::select('firstname','lastname','middlename')->where('account_type','Admin')->first();

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
        $admin = User::select('firstname','lastname','middlename')->where('account_type','Admin')->first();

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

        $transaction = Transaction::all();
        //app-bar
        $admin = User::select('firstname','lastname','middlename')->where('account_type','Admin')->first();
        return view('admin.transactionListPage', compact('transaction','admin'));

    }

    public function completed_transaction(){

        $transaction = Transaction::where('status', 'finished')->get();
        //app-bar
        $admin = User::select('firstname','lastname','middlename')->where('account_type','Admin')->first();
        return view('admin.completedTaskListPage', compact('transaction','admin'));

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
        $audit = Audit::all();
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
        $admin = User::select('firstname','lastname','middlename')->where('account_type','Admin')->first();

        // Return the view with the transaction data for a regular request
        return view('admin.auditTrails', ['transaction' => $audit,'admin'=>$admin]);
    }

    public function new_staff(){
    //app-bar
    $admin = User::select('firstname','lastname','middlename')->where('account_type','Admin')->first();
        return view('admin.newOfficeAccount','admin');
    }
}    
