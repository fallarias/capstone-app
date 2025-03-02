<?php

namespace App\Http\Controllers;

use App\Events\StaffScan;
use App\Events\UserDownload;
use App\Events\UserFile;
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
use App\Events\UserNotification;
use App\Events\UserTaskName;
use App\Events\UserTaskStep;
use App\Models\Audit;
use App\Models\Rate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Requirements;
use Jenssegers\Agent\Agent;
use ZipArchive;
use Carbon\Carbon;
use App\Exports\TaskExport;
use Maatwebsite\Excel\Facades\Excel;


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
            'email' => 'required|string|email|max:255|unique:users|ends_with:isu.edu.ph',
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

    //client homepage
    public function homepage(){

        $UserId = session('user_id');
        // Fetch data from the database as needed
        $documents = Task::where('status', 1)->count();

        $audit = Audit::where('user_id', $UserId)
                            ->whereNotNull('finished')
                            ->count();
        $beyond = Audit::where('user_id', $UserId)
                            //->whereRaw("TIME(start) >= ?", ['16:00:00'])
                            ->whereNull('finished')
                            ->count();

        $requerements = Requirements::where('user_id', $UserId)->count();
        $messages = $audit + $requerements + $beyond;
        $ongoing = 'ongoing';
        $pending = Transaction::where('status', $ongoing)
                                        ->where('user_id', $UserId)
                                        ->count();
        $complete = Transaction::where('status', 'finished')
                                        ->where('user_id', $UserId)
                                        ->count();
        return view('client.clientHomePage', compact('documents','messages','pending','complete'));
        
    }
    
    //client notification
    public function notification()
    {
        $UserId = session('user_id');
    
        $finishedAudits = Audit::with(['user','staff'])->where('user_id', $UserId)
                        // Uncomment if you only want finished audits
                        //->whereNotNull('finished')
                        ->get();

        $auditEntry = Audit::with(['user','staff'])->where('user_id', $UserId)
                        // Uncomment if you only want finished audits
                        ->whereNotNull('start')
                        ->whereRaw("TIME(start) >= ?", ['16:00:00'])
                        ->get();

        // Fetch all requirement messages for the user
        $requirementMessages = Requirements::with(['user','staff'])->where('user_id', $UserId)
                                            ->orderBy('stop_transaction', 'desc')  // Optional: Sort messages by stop_transaction
                                            ->get();  // Get all messages

        if ($finishedAudits->isEmpty()) {
            return view('client.clientNotfication', compact('finishedAudits','requirementMessages','auditEntry'))->with('null', 'No Notification found');
        }                                    
        // Prepare messages for response
        //$messages = $requirementMessages->pluck('message')->filter(); // Get only the messages, filter out any null values
        $user = $user = User::find($UserId);
        event(new UserNotification($user));
        return view('client.clientNotfication', compact('finishedAudits','requirementMessages','auditEntry'));

    }

    //returning the name of the task
    public function template()
    {
        $files = Task::where('status', 1)->get();
    
        foreach ($files as $file) {
            if ($file->type == 'application/pdf') {
                $file->pdfUrl = url(Storage::url($file->filepath));
                $file->wordUrl = null;
            } elseif ($file->type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' || $file->type == 'application/msword') {
                $file->wordUrl = url(Storage::url($file->filepath));
                $file->pdfUrl = null;
            } else {
                $file->pdfUrl = null;
                $file->wordUrl = null;
            }
        }
        $user = User::where('account_type', 'Admin')->first();
        event(new UserFile($user));
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

        $name = Task::select('name')->where('task_id', $task_id)->first();

        $beyondFour = Audit::where('transaction_id', $transaction_id)
                    ->whereRaw("TIME(start) >= ?", ['17:00:00'])
                    ->get();
    
        $user = User::find($UserId);
        
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

                if (is_string($item->New_alloted_time)) {
                    $newAllotedTimeInMinutes = (int)$item->New_alloted_time;
            
                    // Check if the time is less than or equal to 59 minutes
                    if ($newAllotedTimeInMinutes <= 59) {
                        $item['New_alloted_time_display'] = "{$newAllotedTimeInMinutes} minutes";
                    } 
                    // Check if the time is less than or equal to 1380 minutes (23 hours)
                    else if ($newAllotedTimeInMinutes <= 1380) {
                        $item['New_alloted_time_display'] = round($newAllotedTimeInMinutes / 60, 2) . " hours";
                    }// Check if the time is less than or equal to 43200 minutes (30 days)
                    else if ($newAllotedTimeInMinutes <= 43200) {
                        $item['New_alloted_time_display'] = round($newAllotedTimeInMinutes / 1440, 2) . " days";
                    }// Check if the time is less than or equal to 524160 minutes (52 weeks)
                    else if ($newAllotedTimeInMinutes <= 524160) {
                        $item['New_alloted_time_display'] = round($newAllotedTimeInMinutes / 10080, 2) . " weeks";
                    } else {
                        // Handle other cases if needed, e.g., days, weeks
                        $item['New_alloted_time_display'] = "{$newAllotedTimeInMinutes} minutes";
                    }
                } else {
                    // Fallback if New_alloted_time is not a string
                    $item->New_alloted_time_display = "Invalid time format";
                }

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

                if (is_string($item->New_alloted_time)) {
                    $newAllotedTimeInMinutes = (int)$item->New_alloted_time;
            
                    // Check if the time is less than or equal to 59 minutes
                    if ($newAllotedTimeInMinutes <= 59) {
                        $item['New_alloted_time_display'] = "{$newAllotedTimeInMinutes} minutes";
                    } 
                    // Check if the time is less than or equal to 1380 minutes (23 hours)
                    else if ($newAllotedTimeInMinutes <= 1380) {
                        $item['New_alloted_time_display'] = round($newAllotedTimeInMinutes / 60, 2) . " hours";
                    }// Check if the time is less than or equal to 43200 minutes (30 days)
                    else if ($newAllotedTimeInMinutes <= 43200) {
                        $item['New_alloted_time_display'] = round($newAllotedTimeInMinutes / 1440, 2) . " days";
                    }// Check if the time is less than or equal to 524160 minutes (52 weeks)
                    else if ($newAllotedTimeInMinutes <= 524160) {
                        $item['New_alloted_time_display'] = round($newAllotedTimeInMinutes / 10080, 2) . " weeks";
                    } else {
                        // Handle other cases if needed, e.g., days, weeks
                        $item['New_alloted_time_display'] = "{$newAllotedTimeInMinutes} minutes";
                    }
                } else {
                    // Fallback if New_alloted_time is not a string
                    $item->New_alloted_time_display = "Invalid time format";
                }

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

            }
            
        }
        event(new UserTaskStep($user));
        return view('client.clientTrackDocument', compact('task', 'name','beyondFour'));
    }
    
    //returning transaction with status of ongoing only
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

        $user = User::find($UserId);

        event(new UserTaskName($user));
        return view('client.clientTaskList',compact('tasks'));
    }

    //returning all transactions ongoing and finished
    public function transaction_history(){

        $UserId = session('user_id');
        $tasks = Task::join('tbl_transaction', 'task.task_id', '=', 'tbl_transaction.task_id')
                        ->where('tbl_transaction.user_id', $UserId)
                        //->where('tbl_transaction.status', 'ongoing')
                        //->where('tasks.status', 1)
                        ->select('task.*', 'tbl_transaction.transaction_id', 'tbl_transaction.status')
                        ->get();

        $user = User::find($UserId);
        event(new StaffScan($user));
        return view('client.clientTrasactionHistory', compact('tasks'));
            
    }

    //donwloading the word or docx to mobile or laptop web browser
    public function transaction($Id)
    {
        $UserId = session('user_id');
        Log::info('User ID from session:', ['user_id' => $UserId]);
    
        // Retrieve the file path from the Task model
        $task = Task::select('filepath', 'filename')->where('task_id', $Id)->first();
    
        if (!$task || !$task->filepath || !$task->filename) {
            return response()->json(['message' => 'PDF file not found'], 404);
        }
    
        $FilePath = storage_path('app/' . $task->filepath);
    
        if (!file_exists($FilePath)) {
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
            $allottedTime = (int) $createRecord->New_alloted_time;
    
            $transaction = Transaction::create([
                'user_id' => $UserId,
                'task_id' => $Id,
                'Total_Office_of_Request' => $total,
            ]);
    
            Log::info('Transaction created', ['transaction' => $transaction]);
    
            $transactionId = $transaction->transaction_id;
    
            $audit = Audit::create([
                'user_id' => $UserId,
                'task_id' => $Id,
                'office_name' => $createRecord->Office_name,
                'task' => $createRecord->Office_task,
                'transaction_id' => $transactionId,
            ]);
    
            Log::info('Audit created', ['audit' => $audit]);

            $user = User::find($UserId);
            event(new  UserDownload($user));

            // Generate the QR code
            $qrCodeData = json_encode(['userId' => (string) $UserId, 'taskId' => (string) $Id]);
            $qrCode = QrCode::format('png')->size(300)->generate($qrCodeData);
    
            $agent = new Agent();

            // Log the User-Agent for debugging purposes
            Log::info('User-Agent:', ['user_agent' => $agent->getUserAgent()]);

            if ($agent->isMobile()) {
                // Mobile device: Generate and serve ZIP containing QR code and PDF
                // Save QR Code to a temporary location

                //word download
                // $qrCodePath = storage_path("app/public/qr_code_task_{$Id}.png");
                // file_put_contents($qrCodePath, $qrCode);
                // Log::info('QR code saved for mobile at:', ['path' => $qrCodePath]);
    
                // // Create a ZIP file to bundle both the QR code and the PDF
                // $zipPath = storage_path("app/public/transaction_{$Id}.zip");
                // $zip = new ZipArchive;
    
                // if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                //     $zip->addFile($FilePath, $task->filename); // Add PDF to ZIP
                //     $zip->addFile($qrCodePath, "qr_code_task_{$Id}.png"); // Add QR Code to ZIP
                //     $zip->close();
                // } else {
                //     return response()->json(['message' => 'Failed to create ZIP archive'], 500);
                // }
    
                // // Serve the ZIP file for download
                // return response()->download($zipPath)->deleteFileAfterSend(true);

                //EXCEL download
                // Save QR Code to temporary file
                $qrCodePath = storage_path("app/public/qr_code_task_{$Id}.png");
                file_put_contents($qrCodePath, $qrCode);
                Log::info('QR code saved for mobile at:', ['path' => $qrCodePath]);

                // Generate Excel file and save to temporary file
                $excelFilePath = storage_path("app/public/task_data_{$Id}.xlsx");
                Excel::store(new TaskExport($Id), "public/task_data_{$Id}.xlsx");
                Log::info('Excel file generated at:', ['path' => $excelFilePath]);

                // Create ZIP file
                $zipPath = storage_path("app/public/transaction_{$Id}.zip");
                $zip = new ZipArchive;

                if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                    $zip->addFile($qrCodePath, "qr_code_task_{$Id}.png");
                    $zip->addFile($excelFilePath, "task_data_{$Id}.xlsx");
                    $zip->close();
                } else {
                    return response()->json(['message' => 'Failed to create ZIP archive'], 500);
                }

                return response()->download($zipPath)->deleteFileAfterSend(true);

            } else {
                // Get the user's Downloads directory dynamically
                $downloadsDir = getenv('USERPROFILE') . DIRECTORY_SEPARATOR . 'Downloads';
                $qrCodePath = $downloadsDir . DIRECTORY_SEPARATOR . 'qr_code.png';
    
                // Save the QR code to the desktop Downloads folder
                file_put_contents($qrCodePath, $qrCode);
                Log::info('QR code saved for desktop at:', ['path' => $qrCodePath]);
    
                // Copy the PDF to the Downloads folder
                // $pdfDownloadPath = $downloadsDir . DIRECTORY_SEPARATOR . $task->filename;
                // copy($FilePath, $pdfDownloadPath);
    
                //Log::info('PDF saved for desktop at:', ['path' => $pdfDownloadPath]);
    
                //return redirect()->back()->with('success', 'The template is donwloaded.');
                //for excel download
                return Excel::download(new TaskExport($Id), "task_data_{$Id}.xlsx");
            }
        } catch (\Exception $e) {
            Log::error('Transaction creation failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Transaction creation failed.');
        }
    }
        
    //logout logic
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

    //rate page
    public function rate($transaction_id){
        
        $transaction = Audit::with(['user','staff'])->where('transaction_id', $transaction_id)->get(); 
        return view('client.clientRatingPage', compact('transaction'));
    }

    //save client review
    public function review(Request $request)
    {
        $request->validate([
            'ratings' => 'required|array',
            'ratings.*' => 'required|integer|min:1|max:5', // Each rating must be an integer between 1 and 5
            'staff_ids' => 'required|array',
            'staff_ids.*' => 'required|exists:users,user_id', // Ensure each staff_id exists in the users table
            'trans_ids' => 'required|array',
            'trans_ids.*' => 'required|integer', 
        ], [
            'ratings.required' => 'Ratings are required.',
            'ratings.*.required' => 'Each rating is required.',
            'ratings.*.integer' => 'Each rating must be a number.',
            'ratings.*.min' => 'Rating must be at least 1.',
            'ratings.*.max' => 'Rating cannot be more than 5.',
            'staff_ids.required' => 'Staff IDs are required.',
            'staff_ids.*.exists' => 'Selected staff member does not exist.',
        ]);

        $ratings = $request->input('ratings');
        $staffIds = $request->input('staff_ids');
        $trans_id = $request->input('trans_ids');

        foreach ($staffIds as $index => $staffId) {
            $rating = $ratings[$staffId] ?? null; // Get rating at the same index
            $transactionId = $trans_id[$index] ?? null; // Get transaction ID at the same index

            if ($rating !== null && $transactionId !== null) {
                // Store the review in the database
                Rate::updateOrCreate(
                    ['user_id' => $staffId, 'transaction_id' => $transactionId], // Search criteria
                    ['score' => $rating] // Data to update/insert
                );
            }
        }

        return back()->with('success', 'Review submitted successfully.');
    }

}
