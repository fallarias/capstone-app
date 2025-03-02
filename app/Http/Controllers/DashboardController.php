<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Task;
use App\Models\Logs;
use App\Models\Rate;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboard(){

        //app-bar
        $admin = User::where('account_type','Admin')->first();
        
        $date = Carbon::today();
        
        // Get distinct user IDs who have logged in
        $loggedInCount = Logs::select('user_id')
                                ->where('action', 'Login')
                                ->whereDate('Date',$date)
                                ->groupBy('user_id')
                                ->count();

        // Count total users
        $totalUsersCount = User::where('status', 'Accepted')->count();

        // Calculate offline users
        $offlineCount = max(0, $totalUsersCount - $loggedInCount); // Prevent negative values


        $userIds = User::where('account_type', 'office staff')->pluck('user_id');

        // Count logged-in staff
        $loggedInStaff = Logs::whereIn('user_id', $userIds)
            ->where('action', 'Login')
            ->whereDate('Date', $date) // Ensure you're using the correct date column
            ->distinct('user_id') // Count unique user IDs
            ->count('user_id'); // Count the distinct user IDs

        // Count total staff
        $totalStaffCount = User::where('status', 'Accepted')->where('account_type', 'office staff')->count();

        // Calculate offline staff
        $offlineStaff = max(0, $totalStaffCount - $loggedInStaff); // Prevent negative values

        $clientIds = User::where('account_type', 'client')->pluck('user_id');


        // Count logged-in Client
        $loggedInClient = Logs::whereIn('user_id', $clientIds)
                                ->where('action', 'Login')
                                ->whereDate('Date', $date) // Ensure you're using the correct date column
                                ->distinct('user_id') // Count unique user IDs
                                ->count('user_id'); // Count the distinct user IDs

        // Count total client
        $totalClientCount = User::where('status', 'Accepted')->where('account_type', 'client')->count();

        // Calculate offline staff
        $offlineClient = max(0, $totalClientCount - $loggedInClient); // Prevent negative values


        //all users count
        $client = User::where('account_type', 'client')->count();
        $staff = User::where('account_type', 'office staff')->count();
        $admins = User::where('account_type', 'Admin')->count();


        //$supplier = Supplier::count(); //suppliers not create task
        $user = User::whereIn('account_type', ['client', 'office staff', 'supplier'])->count();
        $transaction = Transaction::count();
        $completed = Transaction::where('status', 'finished')->count();
        $activate = Task::where("status", 1)->where('soft_del', 0)->count();
        $users = User::where('status', 'Accepted')
                        ->whereIn('account_type', ['client', 'office staff', 'supplier'])
                        ->count();

        $action = 'Login';
        $current_time = Carbon::today()->toDateString(); // Outputs "2024-12-01"
        Log::info($current_time);
        $online_users = Logs::whereDate('Date', $current_time)
                    ->where('action', $action)
                    ->where(function ($query) {
                        $query->where('account_type', 'office staff')
                            ->orWhere('account_type', 'client');
                    })->get();

        Log::info('Online Users Without Action Filter', $online_users->toArray());
        Log::info('Online Users', $online_users->toArray());                        
        $taskIds = $online_users->pluck('user_id');
        $online = User::whereIn('users.user_id', $taskIds)->get();
        //->join('logs', 'users.user_id', '=', 'logs.user_id') // Ensure you're using users.id
        //->select('users.*', 'logs.Date as login_date') // Adjust to correct date column
        //->where('logs.action', 'Login') // Ensure it only includes login actions
        

        //  line graph
        // Fetch data while excluding null dates
        $data = Transaction::select('created_at', 'transaction_id')
            ->whereNotNull('created_at')
            ->get();

        // Group by Date
        $groupedData = $data->groupBy(function ($item) {
            return Carbon::parse($item->created_at)->format('Y-m-d'); // Group by date
        });

        // Define all days of the week
        $daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        // Initialize counts for each day
        $dailyLabels = $daysOfWeek;
        $dailyValues = array_fill(0, count($daysOfWeek), 0);

        // Map values to the correct day of the week for daily data
        foreach ($groupedData as $date => $group) {
            $dayOfWeek = Carbon::parse($date)->format('l'); // Get the day of the week
            $index = array_search($dayOfWeek, $daysOfWeek); // Find the index of that day

            if ($index !== false) {
                $dailyValues[$index] += count($group); // Use count() to sum items in the group
            }
        }

        // Group by week
        $weeklyGroupedData = $data->groupBy(function ($item) {
            return Carbon::parse($item->Date)->format('W-Y'); // Group by week number and year
        });

        // Prepare labels and values for weekly data
        $weeklyLabels = [];
        $weeklyValues = [];

        foreach ($weeklyGroupedData as $week => $group) {
            $weeklyLabels[] = "Week $week"; // Format label as needed
            $weeklyValues[] = $group->count(); // Count the number of entries for that week
        }

        //bar graph

        $label = User::select('department')
                        ->where('account_type', 'office staff')
                        ->where('status', 'Accepted')
                        ->pluck('department') // Extract the 'department' field
                        ->toArray(); 


        Log::info('Department array', ['department' => $label]);
        // Count total client
        $staffScans = [];

        // Count audits for each department
        foreach ($label as $department) {
            $count = Audit::where('office_name', $department)
                ->whereNotNull('finished')
                //->where('finished', $date)
                ->count();
            
            $staffScans[$department] = $count; // Store count in associative array
        }

        // Log the counts for debugging
        Log::info('Total staff scans', ['staffScans' => $staffScans]);

        // Prepare data for Chart.js
        $data = array_values($staffScans);
        Log::info('Total staff scans', ['count' => $data]);

        //For staff star
        $votes = Rate::with('user')->get()
                    ->groupBy(function ($rate) {
                        return $rate->user->department;
                    });

        $scores = $votes->map(function ($rates, $department) {
            return $rates->sum('score');  
        });

        $departments = $scores->keys(); // ["IT", "HR", "Finance"]
        $stars = $scores->values();

        // Return the admin dashboard view with the data and user count
        return view('admin.dashboard', compact('user', 'activate','transaction', 'online',
                    'users','loggedInCount', 'offlineCount','loggedInStaff', 'offlineStaff',
                    'loggedInClient', 'offlineClient', 'admin','staff','client', 'completed'
                    ,'dailyLabels', 'dailyValues', 'weeklyLabels', 'weeklyValues','label',
                    'data','admins','stars','departments'));
    }

    public function logs(){
        $logs = Logs::all();

        //app-bar
        $admin = User::select('firstname','lastname','middlename')->where('account_type','Admin')->first();

        return view('admin.logsPage', compact('logs','admin'));
    }

    public function getStats() {
        $completed = Transaction::where('status', 'finished')->count();
        $user = User::whereIn('account_type', ['client', 'office staff', 'completed'])->count();
        $transaction = Transaction::count();
        $activate = Task::where("status", 1)->where('soft_del', 0)->count();

        // Return the counts as JSON for AJAX requests
        return response()->json([
            'completed' => $completed,
            'user' => $user,
            'transaction' => $transaction,
            'activate' => $activate,
        ]);
    }

}
