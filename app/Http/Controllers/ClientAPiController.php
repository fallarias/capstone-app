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
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Writer\PDF;
use Exception;

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
    
        $UnfinishedAudits = Audit::with('user')->where('user_id', $userId)
                        // Uncomment if you only want finished audits
                        //->whereNotNull('finished')
                        ->get();

        $finishedAudits = Audit::with('user')->where('user_id', $userId)
                        // Uncomment if you only want finished audits
                        ->whereNotNull('finished')
                        ->get();
                        

        // Fetch all requirement messages for the user
        $requirementMessages = Requirements::with('user')->where('user_id', $userId)
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

    
    //Returning value of chart in client
    public function client_file()
    {
        $files = Task::where('status', 1)->get();
    
        foreach ($files as $file) {
            try {
                Log::info('Processing file: ' . $file->filepath);
                $file->pdfUrl = null;
                $file->wordUrl = null;
    
                if ($file->type === 'application/pdf') {
                    // If the file is already a PDF
                    $file->pdfUrl = url(Storage::url($file->filepath));
                } elseif (in_array($file->type, [
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/msword',
                ])) {
                    // If the file is a Word document
                    $file->pdfUrl = $this->convertDocxToPdf($file->filepath);
                }
            } catch (Exception $e) {
                Log::error('Error processing file: ' . $e->getMessage());
            }
        }
    
        return response(['files' => $files], 200);
    }
    
    public function convertDocxToPdf($filePath)
    {
        // Normalize the file path
        $normalizedPath = str_replace('public/', '', $filePath);
        $fullPath = storage_path('app/public/' . $normalizedPath);
    
        if (!file_exists($fullPath)) {
            throw new Exception("File does not exist at: " . $fullPath);
        }
    
        try {
            // Load the .docx file
            $phpWord = \PhpOffice\PhpWord\IOFactory::load($fullPath);
    
            // Use DomPDF for PDF generation
            $domPdfPath = base_path('vendor/dompdf/dompdf');
            \PhpOffice\PhpWord\Settings::setPdfRendererPath($domPdfPath);
            \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');
    
            // Set the output path for the PDF
            $pdfFileName = basename($normalizedPath, '.docx') . '.pdf';
            $pdfPath = storage_path('app/public/' . $pdfFileName);
    
            // Create the PDF writer
            $pdfWriter = IOFactory::createWriter($phpWord, 'PDF');
            $pdfWriter->save($pdfPath);
    
            Log::info('PDF successfully created: ' . $pdfPath);
    
            // Return the public URL of the converted PDF
            return url('storage/' . $pdfFileName);
        } catch (Exception $e) {
            Log::error('Error converting .docx to PDF: ' . $e->getMessage());
            throw $e;
        }
    }
    
    //Returning Bar Chart
    public function bar_chart($userId)
    {
        // Initialize an array with the days of the week
        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

        // Initialize counts for each day of the week to zero
        $counts = array_fill(0, 7, 0);

        // Fetch audits that match the department and are unfinished
        $transaction = Transaction::where('user_id', $userId)->get();

        foreach ($transaction as $audit) {
            if ($audit->created_at) {
                // Get the day of the week (0=Sun, 1=Mon, ..., 6=Sat)
                $dayIndex = Carbon::parse($audit->created_at)->dayOfWeek;
                
                // Map Sunday (0) to index 6 for consistent array index (Sun at the end)
                $dayIndex = $dayIndex === 0 ? 6 : $dayIndex - 1;

                // Increment the count for the corresponding day
                $counts[$dayIndex]++;
            }
        }

        $data = [
            'days' => $days,
            'values' => $counts,
        ];

        return response()->json($data);
    }

        //Returning all staff scan
    public function client_history($userId){

        $scanned = Transaction::all()->where('user_id', $userId);

        return response()->json($scanned);

    }
}
