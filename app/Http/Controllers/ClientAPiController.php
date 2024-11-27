<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Task;
use App\Models\Create;
use App\Models\Transaction;
use App\Events\UserLoggedOut;
use App\Events\UserLoggedIn;
use App\Models\Audit;
use Carbon\Carbon;
use App\Models\Requirements;

class ClientAPiController extends Controller
{

    //Returning the name of the tasks
    public function task_document($userId)
    {
        $user_id = (int) $userId;
        $transactions = Transaction::where('user_id', $user_id)->where('status', 'ongoing')->get();
        $taskIds = $transactions->pluck('task_id');
        $tasks = Task::whereIn('task_id', $taskIds)
                            ->where('status', 1)
                            ->get();

        Log::info('Lists: ',['transactions' => $transactions, 'tasks' => $tasks]);


        return response(['tasks' => $tasks, 'transactions' => $transactions], 200);
    }

    //Downloading the pdf file and creating new transaction and audit 
    public function transaction(Request $request)
    {
        $attrs = $request->validate([
            'user_id' => 'required',
            'task_id' => 'required',
        ]);
    
        $status = 'ongoing';
        $existingTransaction = Transaction::where('user_id', $attrs['user_id'])
            ->where('task_id', $attrs['task_id'])
            ->where('status', $status)
            ->first();
    
        if ($existingTransaction) {
            return response()->json(['message' => 'Transaction already exists'], 409);
        }
    
        $total = Create::where('task_id', $request->task_id)->count();
        $createRecord = Create::where('task_id', $request->task_id)->first();
    
        try {
            // Ensure New_alloted_time is cast to an integer (or float if needed)
            $allottedTime = (int) $createRecord->New_alloted_time;
    
            // Create the Transaction
            $transaction = Transaction::create([
                'user_id' => $attrs['user_id'],
                'task_id' => $attrs['task_id'],
                'Total_Office_of_Request' => $total,
                //'deadline' => now()->addHours($allottedTime),
            ]);
    
            // Retrieve the newly created transaction_id
            $transactionId = $transaction->transaction_id;
    
            // Ensure transaction_id is passed to the Audit record
            $audit = Audit::create([
                'user_id' => $attrs['user_id'],
                'task_id' => $attrs['task_id'],
                // 'start' => now(),
                // 'deadline' => now()->addHours($allottedTime),
                'office_name' => $createRecord->Office_name,
                'task' => $createRecord->Office_task,
                'transaction_id' => $transactionId, // <-- Ensure this is included
            ]);
    
            Log::info('Audit created', ['audit' => $audit]);
    
            // Return success response with transaction_id for confirmation
            return response()->json(['message' => 'Transaction created successfully', 'transaction_id' => $transactionId], 200);
        } catch (\Exception $e) {
            // Log the error for easier debugging
            Log::error('Transaction creation failed', ['error' => $e->getMessage()]);
    
            return response()->json(['message' => 'Transaction creation failed', 'error' => $e->getMessage()], 500);
        }
    }

    //Returning the value in create Model with the same task id
    public function template_history($id, $user_id)
    {
        $taskId = (int) $id;
        $userId = (int) $user_id;
        $task = Create::where('task_id', $taskId)->get();
        $transaction = Transaction::where('task_id', $taskId)
                                    ->where('status', 'ongoing')
                                    ->where('user_id', $userId)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $officeDone = $transaction->Office_Done;

        if (empty($task)) {
            return response()->json(['message' => 'No task found'], 404);
        }

        // If officeDone is 0, set all tasks to 'Waiting' initially
        if ($officeDone == 0) {
            foreach ($task as &$item) {
                // Set default status to 'Waiting'
                $item['task_status'] = 'Waiting';
    
                // Retrieve the audit entry for the current office
                $auditEntry = Audit::where('transaction_id', $transaction->transaction_id)
                    ->where('office_name', $item->Office_name)
                    ->first();
    
                // Debugging output
                Log::info("Audit Entry for office: {$item->Office_name}", [
                    'start' => $auditEntry->start ?? 'null',
                    'deadline' => $auditEntry->deadline ?? 'null'
                ]);
    
                // Check if start and deadline are not null, update status to 'Ongoing'
                if ($auditEntry && !is_null($auditEntry->start) && !is_null($auditEntry->deadline)) {
                    $item['task_status'] = 'Ongoing';
                }
            }
        } else {
            // Iterate through tasks and update the status based on audit entries and officeDone
            foreach ($task as $index => &$item) {
                $auditEntry = Audit::where('transaction_id', $transaction->transaction_id)
                    ->where('office_name', $item->Office_name)
                    ->first();
    
                // Default task status based on officeDone
                if ($index < $officeDone) {
                    $item['task_status'] = 'Completed';
                } elseif ($index == $officeDone) {
                    $item['task_status'] = 'Ongoing';
                } else {
                    $item['task_status'] = 'Waiting';
                }
    
                // // Additional check for specific `Audit` entry
                // Log::info("Audit Check for office: {$item->Office_name}", [
                //     'start' => $auditEntry->start ?? 'null',
                //     'deadline' => $auditEntry->deadline ?? 'null'
                // ]);
    
                // // If start and deadline are not null, set status to 'Ongoing'
                // if ($auditEntry && !is_null($auditEntry->start) && !is_null($auditEntry->deadline) && !is_null($auditEntry->finished)) {
                //     $item['task_status'] = 'Completed';
                // }
            }
        }

        return response()->json($task);
    }

    //Returning notification to the user/client
    public function notification($user)
    {
        $userId = (int) $user;
    
        $UnfinishedAudits = Audit::where('user_id', $userId)
                        // Uncomment if you only want finished audits
                        //->whereNotNull('finished')
                        ->get();

        $finishedAudits = Audit::where('user_id', $userId)
                        // Uncomment if you only want finished audits
                        ->whereNotNull('finished')
                        ->get();

        if ($UnfinishedAudits->isEmpty()) {
            return response()->json(['messages' => 'No finished audits found'], 404);
        }

        if ($finishedAudits->isEmpty()) {
            return response()->json(['messages' => 'No finished audits found'], 404);
        }

        // Fetch all requirement messages for the user
        $requirementMessages = Requirements::where('user_id', $userId)
                                            ->orderBy('stop_transaction', 'desc')  // Optional: Sort messages by stop_transaction
                                            ->get();  // Get all messages

        // Prepare messages for response
        //$messages = $requirementMessages->pluck('message')->filter(); // Get only the messages, filter out any null values

        return response()->json([
            'UnfinishedAudits' => $UnfinishedAudits,
            'finishedAudits' => $finishedAudits,
            'messages' => $requirementMessages,  // Return all messages
        ]);
    }
    
    //Returning value of chart in client
    public function client_chart($userId){

        $id = (int) $userId;
        // Fetch data from the database as needed
        $availableDocuments = Task::where('status', 1)->count();
    
        $audit = Audit::where('user_id', $id)
                            ->whereNotNull('finished')
                            ->count();
        $requerements = Requirements::where('user_id', $id)->count();
        $messages = $audit + $requerements;
        $ongoing = 'ongoing';
        $pendingDocuments = Transaction::where('status', $ongoing)
                                        ->where('user_id', $id)
                                        ->count();
        $completeDocuments = Transaction::where('status', 'finished')
                                        ->where('user_id', $id)
                                        ->count();
    
        return response()->json([
            'availableDocuments' => $availableDocuments,
            'messages' => $messages,
            'pendingDocuments' => $pendingDocuments,
            'completeDocuments' => $completeDocuments,
        ]);
    }

    //Returning pdf files
    public function client_file()
    {
        $files = Task::where('status', 1)->get();

        foreach ($files as $file) {
            if ($file->type == 'application/pdf') {
                $file->pdfUrl = url(Storage::url($file->filepath));
                $file->htmlContent = null;
            } else {
                $file->htmlContent = null;
                $file->pdfUrl = null;
            }
        }

        return response(['files' => $files], 200);
    }


}
