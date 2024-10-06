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


class ApiController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'middlename' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'account_type' => 'required|string',
            'is_delete' => 'default|active',
            'department' => 'required|string',
            'password' => [
                'required', 'confirmed', 'min:8', 'max:255',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $fields = $request->all();
        $user = User::create($fields);

        $token = $user->createToken($request->email);

        event(new Registered($user));

        return response()->json([
            'user' => $user,
            'token' => $token->plainTextToken,
        ]);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response()->json(['message' => 'The provided credentials are incorrect.'], 401);
        }

        if ($user->status === 'Not Accepted') {
            return response()->json(['message' => 'Your account is not accepted. Please contact the administrator.'], 403);
        }

        $token = $user->createToken($request->email);

        event(new UserLoggedIn($user));

        return response()->json([
            'user' => $user,
            'token' => $token->plainTextToken
        ], 200);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        event(new UserLoggedOut($user));

        $user->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

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

    public function task_document($userId)
    {
        $user_id = (int) $userId;
        $transactions = Transaction::where('user_id', $user_id)->get();
        $taskIds = $transactions->pluck('task_id');
        $tasks = Task::whereIn('task_id', $taskIds)->get();

        Log::info($tasks);

        return response(['tasks' => $tasks], 200);
    }

    public function scanned_data(Request $request)
    {
        Log::info('Received data:', $request->all());

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

            if ($transaction->Total_Office_of_Request > $transaction->Office_Done) {
                // Increment Office_Done count
                $transaction->increment('Office_Done');
                
                // Fetch the current index audit entry based on the incremented Office_Done
                $currentIndex = $transaction->Office_Done - 1; // Adjust for zero-based index
                $currentAudit = Audit::where('user_id', $transaction->user_id)
                    ->where('task_id', $taskId)
                    ->skip($currentIndex)
                    ->first(); // Get the current audit entry based on the incremented Office_Done
                
                if ($currentAudit) {
                    // Update the finished timestamp for the current audit entry
                    $currentAudit->update(['finished' => now()]);
                }

                // If Office_Done matches Total_Office_of_Request, update status and send email
                if ($transaction->Total_Office_of_Request == $transaction->Office_Done) {
                    $transaction->update(['status' => 'finished']);

                    $email_finished = User::where('user_id', $userId)->first();
                    Mail::send('admin.notifPage', ['email' => $email_finished->email], function ($message) use ($email_finished) {
                        $message->to($email_finished->email);
                        $message->subject('Task Finished');
                    });
                }

                $nextCreateEntry = Create::where('task_id', $taskId)
                    ->orderBy('create_id')  // Ensure correct order
                    ->skip($transaction->Office_Done)  // Skip to the next entry based on updated Office_Done
                    ->first(); // This fetches the next entry after the increment
                
                if ($nextCreateEntry) {
                    // Update the deadline in the transaction
                    $newDeadline = (int) $nextCreateEntry->New_alloted_time; // Get new allotted time from the next entry
                    $transaction->deadline = now()->addHours($newDeadline);
                    $transaction->save(); // Save the updated transaction
                
                    // Create a new audit entry
                    Audit::create([
                        'user_id' =>  $transaction->user_id,
                        'task_id' => $nextCreateEntry->task_id,
                        'start' => now(), // Current timestamp as start
                        'deadline' => now()->addHours($newDeadline), // Set deadline based on new allotted time
                        'office_name' => $nextCreateEntry->Office_name, // Next office name from Create
                    ]);
                
                } else {
                    Log::warning('No matching Create entry found for taskId: ' . $taskId);
                    return response()->json(['message' => 'No more records to process.'], 404);
                }
            } else {
                return response()->json(['message' => 'Cannot be scanned.'], 404);
            }
        } else {
            return response()->json(['message' => 'Invalid data.'], 400);
        }
    }


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
            // Ensure New_alloted_time is an integer or float
            $allottedTime = (int) $createRecord->New_alloted_time;

            Transaction::create([
                'user_id' => $attrs['user_id'], 
                'task_id' => $attrs['task_id'], 
                'Total_Office_of_Request' => $total,
                'deadline' => now()->addHours($allottedTime), // Now it should work correctly
            ]);

            Audit::create([
                'user_id' =>  $attrs['user_id'],
                'task_id' => $attrs['task_id'],
                'start' => now(),
                'deadline' => now()->addHours($allottedTime), // Current timestamp // Using details from the Create entry
                'office_name' => $createRecord->Office_name,    // Using details from the Create entry
            ]);

            return response()->json(['message' => 'Transaction created successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Transaction creation failed', 'error' => $e->getMessage()], 500);
        }
    }


    public function template_history($id)
    {
        $taskId = (int) $id;
        $task = Create::where('task_id', $taskId)->get()->toArray();
        $transaction = Transaction::where('task_id', $taskId)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $officeDone = $transaction->Office_Done;

        if (empty($task)) {
            return response()->json(['message' => 'No task found'], 404);
        }

        foreach ($task as $index => &$item) {
            $item['task_status'] = ($index < $officeDone) ? 'finished' : 'pending';
            
           
        }

        return response()->json($task);
    }

}
