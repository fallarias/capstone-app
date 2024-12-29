<?php

namespace App\Http\Controllers;

use App\Events\UserLoggedRegistered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Create;
use App\Models\Transaction;
use App\Models\Client;
use App\Models\Task;
use App\Models\Audit;
use App\Models\Requirements;
use App\Events\UserLoggedIn;
use App\Events\UserLoggedOut;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.loginPage');
    }

    public function login(Request $request) {
        $attrs = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
    
        if (Auth::attempt($attrs)) {
            $request->session()->regenerate();
    
            $user = Auth::user(); 
            session(['user_id' => $user->user_id]);
    
            // Check user account type and handle authorization
            if ($user->account_type === 'office staff') {
                Auth::logout(); // Immediately log out the unauthorized user
                $request->session()->invalidate(); // Invalidate the session
                $request->session()->regenerateToken(); // Regenerate the CSRF token
                return back()->withErrors(['email' => 'You are not authorized to access this area.'])->withInput();
            } elseif ($user->account_type === 'Admin') {
                // Admin specific logic
                event(new UserLoggedIn($user));
                $token = $user->createToken('secret')->plainTextToken;
    
                // Fetch counts for admin dashboard
                $supplier = Create::count();
                $userCount = User::count(); 
                $users = User::all();
                $transaction = Transaction::count();
                $client = Client::count();
    
                return redirect()->route('admin.dashboard')->with([
                    'supplier' => $supplier,
                    'user' => $userCount,
                    'client' => $client,
                    'transaction' => $transaction,
                    'users' => $users,
                ]);
            } elseif ($user->account_type === 'client' && $user->status === 'Accepted') {
                // Client-specific logic
                $UserId = session('user_id');
    
                $documents = Task::where('status', 1)->count();
                $audit = Audit::where('user_id', $UserId)
                              ->whereNotNull('finished')
                              ->count();
                $requirements = Requirements::where('user_id', $UserId)->count();
                $messages = $audit + $requirements;
    
                $ongoing = 'ongoing';
                $pending = Transaction::where('status', $ongoing)
                                       ->where('user_id', $UserId)
                                       ->count();
                $complete = Transaction::where('status', 'finished')
                                       ->where('user_id', $UserId)
                                       ->count();
    
                return redirect()->route('client.clientHomePage', compact('documents', 'messages', 'pending', 'complete'));
            } else {
                Auth::logout(); // Log out any other unauthorized users
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->with('error', 'You are not authorized to access this area.')->withInput();
            }
        }
    
        // Handle login failure
        return back()->withErrors(['email' => 'The provided credentials do not match our records.'])->withInput();
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

    public function admin_profile($id){

        $user = User::where('user_id', $id)->first();

        //app-bar
        $admin = User::select('firstname','lastname','middlename')->where('account_type','Admin')->first();

        return view('admin.person', compact('user','admin'));
    }

    public function user_accept($id){

        $user = User::findOrFail($id);  

        $user->where('user_id', $id)->update(['status' => 'Accepted']);
        return back()->with(['success'=> 'Status update to User Accepted.']);
    }
    public function user_reject($id){

        $user = User::findOrFail($id);  

        $user->where('user_id', $id)->update(['status' => 'Not Accepted']);
        return back()->with(['success'=> 'Status update to User Not Accepted.']);
    }

    public function new_staff(Request $request){
        
        $attrs = $request->validate([
            'first' => 'required|array|min:1',
            'middle' => 'required|array|min:1',
            'last' => 'required|array|min:1',
            'email' => 'required|unique:users,email|array|min:1',
            'password' => 'required|array|min:1',
            'department' => 'required|array|min:1',
            'first.*' => 'required|string',
            'middle.*' => 'required|string',
            'last.*' => 'required|string',
            'email.*' => 'required|ends_with:isu.edu.ph|unique:users,email',
            'password.*' => 'required|string',
            'department.*' => 'required|string',
        ]);
        




        if(!empty($attrs['first']) || !empty($attrs['middle']) || !empty($attrs['last'])
         || !empty($attrs['email']) || !empty($attrs['password']) || !empty($attrs['department'])
        ){ 
    
            $firsts = $request->input('first');
            $middles = $request->input('middle'); 
            $lasts = $request->input('last');
            $emails = $request->input('email');
            $passwords = $request->input('password'); 
            $departments = $request->input('department');
            
            // Iterate over the arrays and group data based on index
            foreach ($firsts as $index => $first) {
                // Retrieve corresponding task and time for each office
                $middleOffice = $middles[$index];
                $lastOffice = $lasts[$index];
                $emailOffice = $emails[$index];
                $passwordOffice = $passwords[$index];
                $departmentOffice = $departments[$index];


                // Save the task details to the 'Create' model
                $register = User::create([
                    'firstname' => $first,
                    'middlename' => $middleOffice,
                    'lastname' => $lastOffice,
                    'email' => $emailOffice,
                    'password' => $passwordOffice, 
                    'department' => $departmentOffice,
                    'account_type' => 'office staff',
                    'status' => 'Accepted',
                ]);
                
                event(new UserLoggedRegistered($register));

                Log::info('create',[$register]);
            }
            
            return redirect()->back()->with('success', 'Creating of new Office Staff is successfully added.');
        }
    }

    public function app_bar(){
        
        $admin = User::select('firstname','lastname','middlename')->where('account_type','Admin')->first();

        return view('components.app-bar',compact('admin'));

    }

    public function audit(Request $request)
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
    }
}
