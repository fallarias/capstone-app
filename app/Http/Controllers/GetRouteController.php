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
        
        $offices = NewOffice::all();
        $name = Task::all();
        return view('admin.createTaskPage', compact('offices', 'name'));

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

        return view('admin.listOfTaskPage', compact('data'));
    }
    public function supplier(){

        $supplier = Supplier::all();

        return view('admin.supplierListPage', compact('supplier'));

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
        return view('admin.activatedTaskListPage', compact('data'));

    }

    public function transaction(){

        $transaction = Transaction::all();
        return view('admin.transactionListPage', compact('transaction'));

    }
    public function user(){
        $user = User::whereIn('account_type', ['client', 'office staff', 'supplier'])->get();
        return view('admin.allUserProfile', compact('user'));
    }

    public function audit_trails(){

        
        // Fetch transactions that are ongoing and have a deadline in the future
        $audit = Audit::where('deadline', '>', now())->get();
            
        Log::info('Found ' . $audit->count() . ' transactions to process.');

        

        // Check if there are transactions to process
        if ($audit->isEmpty()) {
            Log::info('No ongoing transactions with upcoming deadlines found.');
            return;
        }

        

        // Process each transaction
        foreach ($audit as $audits) {

            // Fetch admin user only once
            $email_user = User::where('department', $audits->office_name)->first();

            // Check if an admin user exists
            if (!$email_user) {
                Log::warning('No admin user found to send a deadline reminder email.');
                return;
            }

            // Check if the transaction deadline is within the next 60 minutes
            $minutesToDeadline = now()->diffInMinutes($audits->deadline, false);

            if ($minutesToDeadline <= 30 && $minutesToDeadline > 0) {
                try {
                    // Send the email
                    Mail::send('admin.deadline', ['transaction' => $audits], function ($message) use ($email_user) {
                        $message->to($email_user->email);
                        $message->subject('Reminder: Transaction Deadline Approaching');
                    });

                    // Log success message
                    Log::info('Reminder email sent to ' . $email_user->email . ' for transaction ID ' . $audits->id . '.');
                } catch (Exception $e) {
                    // Log if there's an error during the email sending process
                    Log::error('Failed to send email for transaction ID ' . $audits->id . '. Error: ' . $e->getMessage());
                }
            }
        }

        // Log completion of the command
        Log::info('Transaction deadline check completed.');
        $transaction = Audit::all();
        return view('admin.auditTrails', compact('transaction'));
    }
}
