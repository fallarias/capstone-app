<?php

namespace App\Http\Controllers;

use App\Events\StaffFinish;
use App\Events\StaffGetAUdit;
use App\Events\StaffGetChart;
use App\Events\StaffQRScan;
use App\Events\StaffRequirements;
use App\Events\StaffResume;
use App\Events\UserCHart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Task;
use App\Models\Rate;
use App\Models\Holiday;
use App\Models\NewOffice;
use App\Models\Create;
use App\Models\Transaction;
use App\Events\UserLoggedOut;
use App\Events\UserLoggedIn;
use App\Events\UserNotification;
use App\Models\Audit;
use Carbon\Carbon;
use App\Models\Requirements;

class StaffApiController extends Controller
{
    public function index()
    {
        return User::all();
    }

    //staff registration in mobile
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
    //staff login logic
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

        if ($user->status == 'Accepted') {
            $token = $user->createToken($request->email);

        event(new UserLoggedIn($user));

        return response()->json([
            'user' => $user,
            'token' => $token->plainTextToken
        ], 200);
        }else{
            return response()->json(['message' => 'Your account is not accepted. Please contact the administrator.'], 403);
        }

        
    }
    //staff logout logic    
    public function logout(Request $request)
    {
        $user = $request->user();

        event(new UserLoggedOut($user));

        $user->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    //scanning the qr code
    public function scanned_data(Request $request, $department, $user_id)
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
                ->where('status', 'ongoing')
                ->first();
    
            if (!$transaction) {
                Log::info('No matching transaction found for userId: ' . $userId . ' and taskId: ' . $taskId);
                return response()->json(['message' => 'Transaction not found.'], 404);
            }
            

            //if there are existing null resume transaction with the same user id
            $resume = Requirements::where('user_id', $userId)
                                ->whereNull('resume_transaction')
                                ->first();
            if ($resume){
                Log::info('Cannot be scanned with the resume transaction is null: '. 'User Id: ' . $userId);
                return response()->json(['message' => 'Resume Transaction cannot be null.'], 404);
            }


            // Fetch the current office_name from the Create model based on the current Office_Done
            $currentCreateEntry = Create::where('task_id', $taskId)
                ->orderBy('create_id')  // Ensure correct order
                ->skip($transaction->Office_Done)  // Skip to the current office entry
                ->first();
    
            if ($currentCreateEntry) {
                // Check if the department matches the current office_name
                if ($currentCreateEntry->Office_name !== $department) {
                    Log::warning('Department mismatch. Expected: ' . $currentCreateEntry->Office_name . ', Found: ' . $department);
                    return response()->json(['message' => 'Department mismatch.'], 400);
                }
            } else {
                Log::warning('No matching Create entry found for taskId: ' . $taskId);
                return response()->json(['message' => 'No more records to process.'], 404);
            }
    
            if ($transaction->Total_Office_of_Request > $transaction->Office_Done) {
                // Increment Office_Done count
                //$transaction->increment('Office_Done');
                
                $startTime = $this->calculateStartTime();
                $adjustedDeadline = $this->calculateAdjustedDeadline($startTime, (int)$currentCreateEntry->New_alloted_time * 60); // Convert hours to minutes

                $user = User::find($user_id);

                Log::info($user);

                event (new StaffQRScan($user));

                Audit::where('transaction_id', $transaction->transaction_id)
                    ->where('user_id', $userId)
                    ->where('task_id', $taskId)
                    ->where('office_name', $department)
                    ->update([
                        'staff_id' => $user_id,
                        'start' => $startTime,
                        'deadline' => $adjustedDeadline,
                    ]);
                
                Rate::create([
                    'user_id' => $user_id,
                    'transaction_id' => $transaction->transaction_id,
                ]);

                Transaction::where('transaction_id', $transaction->transaction_id)
                    ->where('task_id', $taskId)
                    ->update([
                        'deadline' => $adjustedDeadline,
                    ]);

                
                // Fetch the current index audit entry based on the incremented Office_Done
                // $currentIndex = $transaction->Office_Done - 1; // Adjust for zero-based index
                // $currentAudit = Audit::where('user_id', $transaction->user_id)
                //     ->where('task_id', $taskId)
                //     ->skip($currentIndex)
                //     ->first(); // Get the current audit entry based on the incremented Office_Done
                
                // if ($currentAudit) {
                //     // Update the finished timestamp for the current audit entry
                //     $currentAudit->update(['finished' => now()]);
                // }
    
                // If Office_Done matches Total_Office_of_Request, update status and send email
                // if ($transaction->Total_Office_of_Request == $transaction->Office_Done) {
                //     $transaction->update(['status' => 'finished']);
    
                //     $email_finished = User::where('user_id', $userId)->first();
                //     Mail::send('admin.notifPage', ['email' => $email_finished->email], function ($message) use ($email_finished) {
                //         $message->to($email_finished->email);
                //         $message->subject('Task Finished');
                //     });
                // }
    
                // $nextCreateEntry = Create::where('task_id', $taskId)
                //     ->orderBy('create_id')  // Ensure correct order
                //     ->skip($transaction->Office_Done)  // Skip to the next entry based on updated Office_Done
                //     ->first(); // This fetches the next entry after the increment
                
                // if ($nextCreateEntry) {
                //     // Update the deadline in the transaction
                //     $newDeadline = (int) $nextCreateEntry->New_alloted_time; // Get new allotted time from the next entry
                //     $transaction->deadline = now()->addHours($newDeadline);
                //     // Log::info('Current office done: ' . $transaction->Office_Done);
                //     // Log::info('Next allotted time from Create table: ' . $nextCreateEntry->New_alloted_time);
                //     // Log::info('Calculated new deadline: ' . now()->addHours($newDeadline));
                //     $transaction->save(); // Save the updated transaction
                //     Log::info('Updated transaction deadline: ' . $transaction->deadline);
                //     // Create a new audit entry
                //     Audit::create([
                //         'user_id' =>  $transaction->user_id,
                //         'task_id' => $nextCreateEntry->task_id,
                //         'transaction_id' => $transaction->transaction_id,
                //         'start' => now(), // Current timestamp as start
                //         'deadline' => now()->addHours($newDeadline), // Set deadline based on new allotted time
                //         'office_name' => $nextCreateEntry->Office_name, // Next office name from Create
                //     ]);
                
                // } else {
                //     Log::warning('No matching Create entry found for taskId: ' . $taskId);
                //     return response()->json(['message' => 'No more records to process.'], 404);
                // }
                
                return response()->json(['success' => 'Scanned Completed.'], 200);
            } else {
                return response()->json(['message' => 'Cannot be scanned.'], 404);
            }
        } else {
            return response()->json(['message' => 'Invalid data.'], 400);
        }
    }

    //getting list of holidays
    function isHoliday($date)
    {
        // Query the holidays table to check if the date exists
        $isHoliday = Holiday::whereDate('holiday_date', $date->toDateString())
            ->exists();

        return $isHoliday;
    }

    //helper for the 5 PM time in the scanned_data
    function calculateStartTime()
    {
        $currentTime = now();
    
        // Define the cutoff time as 5:00 PM
        $cutoffTime = $currentTime->copy()->setTime(17, 0); // 5:00 PM
        $nextDayStartTime = $currentTime->copy()->addDay()->setTime(8, 30); // 8:30 AM next day
    
        // If the current time is after the cutoff, calculate the next valid start time
        if ($currentTime->greaterThanOrEqualTo($cutoffTime)) {
            do {
                // Check if tomorrow is a weekend or holiday
                if ($nextDayStartTime->isWeekend() || $this->isHoliday($nextDayStartTime)) {
                    // Move to the next weekday if it's a weekend or holiday
                    $nextDayStartTime = $nextDayStartTime->addDay();
                } else {
                    break; // Valid working day
                }
            } while (true);
    
            return $nextDayStartTime; // Adjusted start time
        }
    
        // If the current time is before the cutoff, no adjustment needed
        return $currentTime;
    }
       

    function calculateAdjustedDeadline($startTime, $durationInMinutes)
    {
        $remainingMinutes = $durationInMinutes;
        $currentTime = $startTime->copy();
    
        while ($remainingMinutes > 0) {
            // Define workday start and end times
            $workStart = $currentTime->copy()->setTime(8, 30);
            $workEnd = $currentTime->copy()->setTime(17, 0); // 5:00 PM
    
            if ($currentTime->greaterThanOrEqualTo($workEnd)) {
                // Move to the next workday start time
                $currentTime = $currentTime->copy()->addDay()->setTime(8, 30);
    
                // Skip weekends and holidays
                while ($currentTime->isWeekend() || $this->isHoliday($currentTime)) {
                    $currentTime = $currentTime->copy()->addDay();
                }
    
                continue;
            }
    
            // Calculate available working minutes in the current day
            $availableMinutes = min($remainingMinutes, $currentTime->diffInMinutes($workEnd));
    
            // Deduct available minutes and advance the time
            $remainingMinutes -= $availableMinutes;
            $currentTime->addMinutes($availableMinutes);
        }
    
        return $currentTime;
    }
    
    //Returning notification to the office staff
    public function staff_notification()
    {
        // Fetch transactions where 'start' and 'deadline' are not null (first update)
        // This will include transactions where 'start' and 'deadline' are set after the initial creation
        $transactions = Audit::whereNotNull('start')
                            ->whereNotNull('deadline')
                            ->get();

        $message = NewOffice::with('user')->get();

        // Return both sets of transactions
        return response()->json([
            'transactions' => $transactions,       // First update where 'start' and 'deadline' are initially set // Latest updates, sorted by most recent 'updated_at'
            'message' => $message,
        ]);
    }

    //Returning notification to the user/client with lack of requirements
    public function lack_Requirements(Request $request, $transaction_id, $department, $user_id)
    {
        $attrs = $request->validate([
            'message' => 'required|string',
        ]);
    
        $transactionId = (int) $transaction_id;
        Log::info('Incoming request:', $request->all());
    
        // Retrieve the transaction
        $transaction = Transaction::find($transactionId);
        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found.'], 404);
        }
    
        // Create the message and stop the transaction
        Requirements::create([
            'message' => $attrs['message'],
            'stop_transaction' => now(),
            'transaction_id' => $transactionId,
            'user_id' => $transaction->user_id, // Associate with the user
            'department' => $department,
        ]);
        $user = User::find($user_id);
        event(new StaffRequirements($user));
    
        return response()->json(['message' => 'Stop the transaction and sent message to the client']);
    }
    
    //Resuming the transaction
    public function resume_transaction($transaction_id, $department, $user_id)
    {
        // Find the requirement entry based on transaction_id and department
        $requirement = Requirements::where('transaction_id', $transaction_id)
                                    ->where('department', $department)
                                    ->whereNull('resume_transaction')
                                    ->firstOrFail();
    
        $existingTransaction = Requirements::where('user_id',  $transaction_id)
                                ->whereNotNull('resume_transaction')
                                ->where('department', $department)
                                ->first();
    
        if ($existingTransaction) {
            return response()->json(['message' => 'Transaction already exists'], 409);
        }
    
        if ($requirement) {
            // Get the stop_transaction and calculate the time difference when resuming
            $stopTime = Carbon::parse($requirement->stop_transaction);
            $resumeTime = Carbon::now();
    
            // Update the resume_transaction timestamp
            $requirement->update([
                'resume_transaction' => $resumeTime,
            ]);
    
            // Calculate the difference in minutes between stop and resume
            $timeDifferenceInMinutes = $stopTime->diffInMinutes($resumeTime);
    
            // Find the corresponding transaction
            $transaction = Transaction::where('transaction_id', $transaction_id)->first();
            $audit = Audit::where('transaction_id', $transaction_id)->first();
    
            // Debug: Check if transaction and audit were found
            if (!$transaction) {
                return response()->json(['message' => 'Transaction record not found'], 404);
            }
    
            if (!$audit) {
                return response()->json(['message' => 'Audit record not found'], 404);
            }
    
            // Debug: Log the current and new deadlines
            Log::info('Old Transaction Deadline:', ['deadline' => $transaction->deadline]);
            Log::info('Old Audit Deadline:', ['deadline' => $audit->deadline]);
    
            // Update the deadlines
            $newTransactionDeadline = Carbon::parse($transaction->deadline)->addMinutes($timeDifferenceInMinutes);
            $newAuditDeadline = Carbon::parse($audit->deadline)->addMinutes($timeDifferenceInMinutes);
            $newAuditStart = Carbon::parse($audit->start)->addMinutes($timeDifferenceInMinutes);
    
            $transaction->update([
                'deadline' => $newTransactionDeadline,
            ]);
    
            $audit->update([
                'start' => $newAuditStart,
                'deadline' => $newAuditDeadline,
            ]);

            $user = User::find($user_id);
            event(new StaffResume($user));

            // Debug: Log the updated deadlines
            Log::info('Updated Transaction Deadline:', ['deadline' => $newTransactionDeadline]);
            Log::info('Updated Audit Deadline:', ['deadline' => $newAuditDeadline]);
    
            return response()->json(['message' => 'Transaction resumed successfully'], 200);
        }
        //$transaction = Transaction::where('transaction_id', $transaction_id)->first();
        return response()->json(['message' => 'Transaction not found'], 404);
    }

    //finish the transaction
    public function finish_transaction(Request $request, $transaction_id, $department, $audit_id, $user_id)
    {
        // Log::info('Received data:', $request->all());
    
        // $request->validate([
        //     'scanned_data' => 'required|array',
        //     'scanned_data.*.userId' => 'required|string',
        //     'scanned_data.*.taskId' => 'required|string',
        // ]);
    
        // $data = $request->input('scanned_data');
    
        // if (!empty($data) && is_array($data)) {
        //     $userId = $data[0]['userId'];
        //     $taskId = $data[0]['taskId'];
    
            // Log::info('User ID: ' . $userId . ', Task ID: ' . $taskId);
    
            $transaction = Transaction::where('transaction_id', $transaction_id)
                 ->where('status', 'ongoing')
                 ->first();
    
            // if (!$transaction) {
            //     Log::info('No matching transaction found for userId: ' . $userId . ' and taskId: ' . $taskId);
            //     return response()->json(['message' => 'Transaction not found.'], 404);
            // }
            

            //if there are existing null resume transaction with the same user id
            $resume = Requirements::where('transaction_id', $transaction_id)
                                ->whereNull('resume_transaction')
                                ->first();
            if ($resume){
                Log::info('Cannot be scanned with the resume transaction is null: '. 'Transaction Id: ' . $transaction_id);
                return response()->json(['message' => 'Resume Transaction cannot be null.'], 404);
            }


            // Fetch the current office_name from the Create model based on the current Office_Done
            // $currentCreateEntry = Create::where('task_id', $taskId)
            //     ->orderBy('create_id')  // Ensure correct order
            //     ->skip($transaction->Office_Done)  // Skip to the current office entry
            //     ->first();
    
            // if ($currentCreateEntry) {
            //     // Check if the department matches the current office_name
            //     if ($currentCreateEntry->Office_name !== $department) {
            //         Log::warning('Department mismatch. Expected: ' . $currentCreateEntry->Office_name . ', Found: ' . $department);
            //         return response()->json(['message' => 'Department mismatch.'], 400);
            //     }
            // } else {
            //     Log::warning('No matching Create entry found for taskId: ' . $taskId);
            //     return response()->json(['message' => 'No more records to process.'], 404);
            // }
    
            if ($transaction->Total_Office_of_Request > $transaction->Office_Done) {
                // Increment Office_Done count
                $transaction->increment('Office_Done');
                
                Audit::where('transaction_id',$transaction->transaction_id)
                        ->where('office_name',$department)
                        ->where('audit_id', $audit_id)
                        ->update([
                            'finished' => now()
                        ]);


                // Fetch the current index audit entry based on the incremented Office_Done
                // $currentIndex = $transaction->Office_Done - 1; // Adjust for zero-based index
                // $currentAudit = Audit::where('user_id', $transaction->user_id)
                //     ->where('task_id', $taskId)
                //     ->skip($currentIndex)
                //     ->first(); // Get the current audit entry based on the incremented Office_Done
                
                // if ($currentAudit) {
                //     // Update the finished timestamp for the current audit entry
                //     $currentAudit->update(['finished' => now()]);
                // }
    
                // If Office_Done matches Total_Office_of_Request, update status and send email
                if ($transaction->Total_Office_of_Request == $transaction->Office_Done) {
                    $transaction->update(['status' => 'finished']);
    
                    $email_finished = User::where('user_id', $transaction->user_id)->first();
                    Mail::send('admin.notifPage', ['email' => $email_finished->email], function ($message) use ($email_finished) {
                        $message->to($email_finished->email);
                        $message->subject('Task Finished');
                    });
                    return response()->json(['success' => 'Task is set to finish.'], 200);
                }
    
                $nextCreateEntry = Create::where('task_id', $transaction->task_id)
                    ->orderBy('create_id')  // Ensure correct order
                    ->skip($transaction->Office_Done)  // Skip to the next entry based on updated Office_Done
                    ->first(); // This fetches the next entry after the increment

                if (!$nextCreateEntry) {
                    Log::warning('No matching Create entry found for taskId: ' . $transaction->task_id);
                    return response()->json(['message' => 'No more records to process.'], 404);
                }

                if ($nextCreateEntry) {
                    // Update the deadline in the transaction
                    $newDeadline = (int) $nextCreateEntry->New_alloted_time; // Get new allotted time from the next entry
                    $transaction->deadline = now()->addHours($newDeadline);
                    // Log::info('Current office done: ' . $transaction->Office_Done);
                    // Log::info('Next allotted time from Create table: ' . $nextCreateEntry->New_alloted_time);
                    // Log::info('Calculated new deadline: ' . now()->addHours($newDeadline));
                    $transaction->save(); // Save the updated transaction
                    Log::info('Updated transaction deadline: ' . $transaction->deadline);

                    // Create a new audit entry
                    Audit::create([
                        'user_id' =>  $transaction->user_id,
                        'task_id' => $nextCreateEntry->task_id,
                        'transaction_id' => $transaction->transaction_id,
                        'task' => $nextCreateEntry->Office_task,
                        'office_name' => $nextCreateEntry->Office_name, // Next office name from Create
                    ]);
                    
                } else {
                    Log::warning('No matching Create entry found for taskId: ' . $transaction->task_id);
                    return response()->json(['message' => 'No more records to process.'], 404);
                }
                $user = User::find($user_id);
                event(new StaffFinish($user));

                return response()->json(['success' => 'Task is set to finish.'], 200);
            } else {
                return response()->json(['message' => 'Cannot be scanned.'], 404);
            }

    }

    //Checking Resume of transaction
    public function check_resume_transaction($transaction_id, $department)
    {
        // Find the requirement entry based on transaction_id and department
        $requirement = Requirements::where('transaction_id', $transaction_id)
                                ->where('department', $department)
                                ->whereNull('resume_transaction')
                                ->first();

        if ($requirement) {
            Log::info('Checked resume transaction status', ['can_resume' => true]);
            return response()->json(['can_resume' => true]);
        } else {
            Log::info('Checked resume transaction status', ['can_resume' => false]);
            return response()->json(['can_resume' => false]);
        }
        
    }

    //Returning value of chart in client
    public function staff_chart($userId)
    {

        $user = User::find($userId);
        // Fetch data from the database as needed
        $pending = Audit::whereNull('finished')
                            ->where('office_name', $user->department)
                            ->where('staff_id', $user->user_id)
                            ->count();
    
        $completed = Audit::whereNotNull('finished')
                            ->where('office_name', $user->department)
                            ->where('staff_id', $user->user_id)
                            ->count();

        event(new StaffGetChart($user));
        return response()->json([
            'pending' => $pending,
            'completed' => $completed,
        ]);
    }

    //Returning value of chart in client
    public function staff_table($userId)
    {

        $user = User::find($userId);
    
        $completed = Audit::whereNotNull('finished')
                            ->where('office_name', $user->department)
                            ->get();
        event(new StaffGetChart($user));
        return response()->json($completed);
    }

    //messaging another office
    public function message_office(Request $request, $department, $user_id)
    {
        $attrs = $request->validate([
            'message' => 'required|string',
            'target_department' => 'required|string',
        ]);
    
        // $transactionId = (int) $transaction_id;
        // Log::info('Incoming request:', $request->all());
    
        // Retrieve the transaction
        // $transaction = Transaction::find($transactionId);
        // if (!$transaction) {
        //     return response()->json(['message' => 'Transaction not found.'], 404);
        // }
    
        // Create the message and stop the transaction
        NewOffice::create([
            'user_id' => $user_id,
            'department' =>  $department,
            'message' => $attrs['message'],
            // 'stop_transaction' => now(),
            // 'transaction_id' => $transactionId,
            // 'user_id' => $transaction->user_id, // Associate with the user
            'target_department' =>  $attrs['target_department'],
        ]);
    
        return response()->json(['message' => 'Stop the transaction and sent message to the client']);
    }

    //Returning all office staff
    public function all_office($department){

        $allOffice = User::select('department')
                            ->where('account_type', 'office staff')
                            ->where('status', 'Accepted')
                            ->where('department', '<>', $department) 
                            ->pluck('department');
                            
        return response()->json($allOffice);

    }

    //Returning all staff scan
    public function staff_scanned_history($department){

        $scanned = Audit::whereNotNull('finished')
                            ->where('office_name', $department)
                            ->get();

        $user = User::where('account_type', 'Admin')->first();

        if ($user) {
            event(new UserChart($user));
        }

        return response()->json($scanned);

    }
    

    //Returning Line Chart
    public function line_chart($department)
    {
        // Initialize an array with the days of the week
        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

        // Initialize counts for each day of the week to zero
        $counts = array_fill(0, 7, 0);

        // Fetch audits that match the department and are unfinished
        $audits = Audit::whereNotNull('finished')
            ->where('office_name', $department)
            ->get();

        foreach ($audits as $audit) {
            if ($audit->finished) {
                // Get the day of the week (0=Sun, 1=Mon, ..., 6=Sat)
                $dayIndex = Carbon::parse($audit->finished)->dayOfWeek;
                
                // Map Sunday (0) to index 6 for consistent array index (Sun at the end)
                $dayIndex = $dayIndex === 0 ? 6 : $dayIndex - 1;

                // Increment the count for the corresponding day
                $counts[$dayIndex]++;
            }
        }

        
        $user = User::where('account_type', 'Admin')->first();

        if ($user) {
            event(new UserChart($user));
        }
        
        $data = [
            'days' => $days,
            'values' => $counts,
        ];
        
        return response()->json($data);
    }


    // public function vue(){
    //     $user = User::all();

    //     return response()->json($user);
    // }
    
}
