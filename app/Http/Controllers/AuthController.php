<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Create;
use App\Models\Transaction;
use App\Models\Client;
use App\Events\UserLoggedIn;
use App\Events\UserLoggedOut;
use Illuminate\Http\RedirectResponse;

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

            event(new UserLoggedIn($user));
            // Create a token for the authenticated user
            $token = $user->createToken('secret')->plainTextToken;

            // Fetch counts for data and user
            $supplier = Create::count();
            $user = User::count(); 
            $users = User::all();
            $transaction = Transaction::count();
            $client = Client::count();
            // Return the admin dashboard view with the data and user count
            return redirect()->route('admin.dashboard')
                            ->with(['supplier' => $supplier, 'user' => $user, 'client' => $client
                            ,'transaction' => $transaction, 'users' => $users]);
        }

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

        return view('admin.person', compact('user'));
    }


}
