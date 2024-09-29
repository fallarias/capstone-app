<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use App\Events\UserLoggedOut;
use App\Events\UserLoggedIn;
use App\Models\Task;
use App\Models\Create;
use App\Models\Transaction;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Auth;


class ApiController extends Controller
{
    public function index()
    {
        return User::all();

    }
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'middlename' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'account_type' => 'required|string',
            'is_delete' => 'default|active',
            'department' => 'required|string',
            'password' => ['required', 'confirmed', 'min:8', 'max:255', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
        ]);

        

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } 
        else {

            $fields = $request->all();
            $user = User::create($fields);

            $token  = $user->createToken($request->email);

            
            event(new Registered($user));

            return response()->json([
                'user' => $user,
                'token' => $token->plainTextToken,
            ]);
        }
    }


    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ]);
        

        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.'
            ], 401); 
        }

        if ($user->status === 'Not Accepted') {
            return response()->json([
                'message' => 'Your account is not accepted. Please contact the administrator.'
            ], 403); // Forbidden status code
        }
        
        $token = $user->createToken($request->email);

        event(new UserLoggedIn($user));
        
        return response()->json([
            'user' => $user,
            'token' => $token->plainTextToken
        ], 200); 
    }

    
    public function logout(Request $request) {
        $user = $request->user();
    

        event(new UserLoggedOut($user));
    

        $user->tokens()->delete();
    

        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }


    
    public function client_file() {
        $files = Task::where('status', 1)->get();
    
        foreach ($files as $file) {
            if ($file->type == 'application/pdf') {
                // Use Storage::url() to get the URL
                $file->pdfUrl = url(Storage::url($file->filepath));
                
                // Remove the .pdf extension from the file name
                //$file->filename = pathinfo($file->filename, PATHINFO_FILENAME);
                
                $file->htmlContent = null;
            } else {
                $file->htmlContent = null;
                $file->pdfUrl = null;
            }
        }
    
        return response([
            'files' => $files
        ], 200);
    }

    public function task_document($userId) {
        $user_id = (int) $userId;
    
        // Get all transactions for the user
        $transactions = Transaction::where('user_id', $user_id)->get();
    
        // Get task IDs from the transactions
        $taskIds = $transactions->pluck('task_id');
    
        // Retrieve tasks based on the collected task IDs
        $tasks = Task::whereIn('task_id', $taskIds)->get();
    
        Log::info($tasks);
    
        return response([
            'tasks' => $tasks
        ], 200);
    }
    

    public function scanned_data(Request $request) {
        Log::info('Received data:', $request->all());
    
        // Validate incoming data
        $request->validate([
            'scanned_data' => 'required|array',
            'scanned_data.*.userId' => 'required|string',
            'scanned_data.*.taskId' => 'required|string',
        ]);
    
        $data = $request->input('scanned_data');
    
        if (!empty($data) && is_array($data)) {
            $userId = $data[0]['userId'];
            $taskId = $data[0]['taskId'];
    
            Log::info('User ID: ' . $userId . ', Task ID: ' . $taskId);
    
            $transaction = Transaction::where('user_id', $userId)
                ->where('task_id', $taskId)
                ->first();
    
            if (!$transaction) {
                Log::info('No matching transaction found for userId: ' . $userId . ' and taskId: ' . $taskId);
                return response()->json(['message' => 'Transaction not found.'], 404);
            }
    
                Log::info('Total Office of Request: ' . $transaction->Total_Office_of_Request . ', Office Done: ' . $transaction->Office_Done);
    
                if ($transaction->Total_Office_of_Request > $transaction->Office_Done) {
                    $transaction->increment('Office_Done');
                    Log::info('Total Office of Request: ' . $transaction->Total_Office_of_Request . ', Office Done: ' . $transaction->Office_Done);
                    if ($transaction->Total_Office_of_Request == $transaction->Office_Done) {
                        $transaction->where('user_id', $userId)
                        ->where('task_id', $taskId)
                        ->update(['status' => 'finished']);
                        //return response()->json(['message' => 'Transaction finished!'], 200);
                        
                        //return response()->json(['success' => 'OTP sent to your email.']);
                    }
            
                    //return response()->json(['message' => 'Data stored successfully!'], 200);
                    
                } else {
                    Log::info('No matching record found for userId: ' . $userId . ' and taskId: ' . $taskId);
                    return response()->json(['message' => 'Cannot be scanned.'], 404);
                }
        } else {
            Log::info('Parsed QR code:', ['error' => 'Unable to parse QR code data']);
            return response()->json(['message' => 'Invalid data.'], 400);
        }
    }
    
    
    

    public function transaction(Request $request){
        $attrs = $request->validate([
            'user_id' => 'required',
            'task_id' => 'required', 
        ]);

        $total = Create::where('task_id', $request->task_id)->count();
        try {
            Transaction::create([
                'user_id' => $attrs['user_id'], 
                'task_id' => $attrs['task_id'], 
                'Total_Office_of_Request' => $total,
            ]);
            return response()->json(['message' => 'Transaction created successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Transaction creation failed', 'error' => $e->getMessage()], 500);
        }
        
    }

    public function template_history($id) {
        $taskId = (int) $id;
    
        // Fetch the tasks associated with the task ID
        $task = Create::where('task_id', $taskId)->get()->toArray();
        
        // Fetch the corresponding transaction for the task
        $transaction = Transaction::where('task_id', $taskId)->first();
    
        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }
    
        $officeDone = $transaction->Office_Done;
    
        if (empty($task)) {
            return response()->json(['message' => 'No task found'], 404);
        }
    
        // Loop through each office and assign task status based on progress
        foreach ($task as $index => &$item) {
            if ($index < $officeDone) {
                $item['task_status'] = 'finished'; // Mark as finished based on Office_Done count
            } else {
                $item['task_status'] = 'pending';  // Mark as pending otherwise
            }
        }
    
        return response()->json($task);
    }
    
    
    
    
}
