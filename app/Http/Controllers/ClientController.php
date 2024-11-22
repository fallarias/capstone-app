<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Task;
use App\Models\Create;
use App\Models\Transaction;
use App\Events\UserLoggedOut;
use App\Models\Audit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Requirements;

class ClientController extends Controller
{
    //for client website 
    public function client_registration(){

        return view('client.registration');
        
    }

    //client registration post
    public function client_registration_create(Request $request)
    {
        $validator = $request->validate([
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'middlename' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'is_delete' => 'default|active',
            'department' => 'required|string',
            'password' => [
                'required', 'min:8', 'max:255',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
            'password_confirmation' => [
                'required', 'min:8', 'max:255',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
        ]);


        if(!$validator['password'] == $validator['password_confirmation']){

            return redirect()->back()->with('error', 'Password does not match.');

        }
        else{
            $account = 'client';
            $user = User::create([
                'firstname' => $validator['firstname'],
                'middlename' => $validator['middlename'],
                'lastname' => $validator['lastname'],
                'email' => $validator['email'],
                'password' => $validator['password'],
                'department' => $validator['department'],
                'account_type' => $account,
            ]);
    
            $token = $user->createToken($request->email);
    
            event(new Registered($user));
    
            return redirect()->back()->with('success', 'Successfully progressed.');
        }

    }

    public function homepage(){

        $UserId = session('user_id');
        // Fetch data from the database as needed
        $documents = Task::where('status', 1)->count();

        $audit = Audit::where('user_id', $UserId)
                            ->whereNotNull('finished')
                            ->count();
        $requerements = Requirements::where('user_id', $UserId)->count();
        $messages = $audit + $requerements;
        $ongoing = 'ongoing';
        $pending = Transaction::where('status', $ongoing)
                                        ->where('user_id', $UserId)
                                        ->count();
        $complete = Transaction::where('status', 'finished')
                                        ->where('user_id', $UserId)
                                        ->count();
        return view('client.clientHomePage', compact('documents','messages','pending','complete'));
        
    }
    
    public function notification()
    {
        $UserId = session('user_id');
    
        $finishedAudits = Audit::where('user_id', $UserId)
                        // Uncomment if you only want finished audits
                        ->whereNotNull('finished')
                        ->get();



        // Fetch all requirement messages for the user
        $requirementMessages = Requirements::where('user_id', $UserId)
                                            ->orderBy('stop_transaction', 'desc')  // Optional: Sort messages by stop_transaction
                                            ->get();  // Get all messages

        if ($finishedAudits->isEmpty()) {
            return view('client.clientNotfication', compact('finishedAudits','requirementMessages'))->with('null', 'No Notification found');
        }                                    
        // Prepare messages for response
        //$messages = $requirementMessages->pluck('message')->filter(); // Get only the messages, filter out any null values
        return view('client.clientNotfication', compact('finishedAudits','requirementMessages'));

    }


    public function template(){

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

        return view('client.clientTemplate', compact('files'));
        
    }

    //Returning the list of step in the transaction
    public function track_document($task_id, $transaction_id)
    {
        $UserId = session('user_id');
        $task = Create::where('task_id', $task_id)->get();
        $transaction = Transaction::where('task_id', $task_id)
                                    ->where('transaction_id', $transaction_id)
                                    ->where('user_id', $UserId)->first();
    
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
                $auditEntry = Audit::where('transaction_id', $transaction_id)
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
                $auditEntry = Audit::where('transaction_id', $transaction_id)
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
    
        return view('client.clientTrackDocument', compact('task'));
    }
    
    
    

    public function task_document()
    {
        $UserId = session('user_id');
        // $transactions = Transaction::where('user_id', $UserId)->where('status', 'ongoing')->get();
        // $taskIds = $transactions->pluck('task_id');
        // $tasks = Task::whereIn('task_id', $taskIds)
        //                     ->where('status', 1)
        //                     ->get();

        // Log::info($tasks);
        $tasks = Task::join('tbl_transaction', 'task.task_id', '=', 'tbl_transaction.task_id')
                        ->where('tbl_transaction.user_id', $UserId)
                        ->where('tbl_transaction.status', 'ongoing')
                        //->where('tasks.status', 1)
                        ->select('task.*', 'tbl_transaction.transaction_id')
                        ->get();

        Log::info($tasks);


        return view('client.clientTaskList',compact('tasks'));
    }


    public function transaction_history(){

        $UserId = session('user_id');
        $tasks = Task::join('tbl_transaction', 'task.task_id', '=', 'tbl_transaction.task_id')
                        ->where('tbl_transaction.user_id', $UserId)
                        //->where('tbl_transaction.status', 'ongoing')
                        //->where('tasks.status', 1)
                        ->select('task.*', 'tbl_transaction.transaction_id')
                        ->get();

        return view('client.clientTrasactionHistory', compact('tasks'));
        
    }

    public function transaction($Id)
    {
        $UserId = session('user_id');
        Log::info('User ID from session:', ['user_id' => $UserId]);
        // Retrieve the file path from the Task model
        $task = Task::select('filepath','filename')->where('task_id', $Id)->first();

        // Check if the task exists and has a filepath
        if (!$task || !$task->filepath ||!$task->filename) {
            return response()->json(['message' => 'PDF file not found'], 404);
        }

        // Construct the full path to the PDF file
        $pdfFilePath = storage_path('app/' . $task->filepath); // Adjust based on how your file paths are structured

        // Check if the PDF file exists
        if (!file_exists($pdfFilePath)) {
            return response()->json(['message' => 'PDF file not found'], 404);
        }
    
        $status = 'ongoing';
        $existingTransaction = Transaction::where('user_id', $UserId)
            ->where('task_id', $Id)
            ->where('status', $status)
            ->first();
    
        if ($existingTransaction) {
            return response()->json(['message' => 'Transaction already exists'], 409);
        }
    
        $total = Create::where('task_id', $Id)->count();
        $createRecord = Create::where('task_id', $Id)->first();
    
        try {
            // Ensure New_alloted_time is cast to an integer (or float if needed)
            $allottedTime = (int) $createRecord->New_alloted_time;
    
            // Create the Transaction
            $transaction = Transaction::create([
                'user_id' => $UserId,
                'task_id' => $Id,
                'Total_Office_of_Request' => $total,
                //'deadline' => now()->addHours($allottedTime),
            ]);
            Log::info('Transaction created', ['transaction' => $transaction]);
    
            // Retrieve the newly created transaction_id
            $transactionId = $transaction->transaction_id;
    
            // Ensure transaction_id is passed to the Audit record
            $audit = Audit::create([
                'user_id' => $UserId,
                'task_id' => $Id,
                // 'start' => now(),
                // 'deadline' => now()->addHours($allottedTime),
                'office_name' => $createRecord->Office_name,
                'transaction_id' => $transactionId,
            ]);
    
            Log::info('Audit created', ['audit' => $audit]);

            // Generate the QR code with both UserId and TaskId
            $qrCodeData = json_encode(['userId' => (string) $UserId, 'taskId' => (string) $Id]);
            $qrCode = QrCode::format('png')->size(300)->generate($qrCodeData);
            
            // Save the QR code to a specified path
            $qrCodePath = 'C:/Users/Fallaria/Downloads/qr_code.png'; // Adjust the path as necessary
            file_put_contents($qrCodePath, $qrCode);
            Log::info('QR code generated and saved at:', ['path' => $qrCodePath]);
            
            // Return the PDF file as a download response
            return response()->download($pdfFilePath,$task->filename);
            
        } catch (\Exception $e) {
            // Log the error for easier debugging
            Log::error('Transaction creation failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Transaction creation failed.');
        }
    }
    
    public function logout(Request $request): RedirectResponse {

        // Get the authenticated user before logging out
        $user = Auth::guard('web')->user();

        if ($user) {
            event(new UserLoggedOut($user));
        }

        Auth::guard('web')->logout();
    
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect('/');
    }

}
