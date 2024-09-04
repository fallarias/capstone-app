<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\File;
use App\Models\Transaction;
use App\Models\Client;

class ApiController extends Controller
{
    public function register(Request $request){
        
        
        try {
            $attrs = $request->validate([
                'firstname' => 'required',
                'lastname' => 'required',
                'gender' => 'required',
                'account_type' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6|confirmed',
                'department' => 'required',
                'photo' => 'required|file|mimes:jpeg,jpg,png,pdf,zip,doc,docx|max:10240',
            ]);
            foreach ($request->file('photo') as $file) {
                
                $filePath = $file->store('public');
                User::create([
                    'email' => $attrs['email'],
                    'password' => bcrypt($attrs['password']), // Make sure to hash the password
                    'lastname' => $attrs['lastname'],
                    'gender' => $attrs['gender'],
                    'account_type' => $attrs['account_type'],
                    'firstname' => $attrs['firstname'],
                    'department' => $attrs['department'],
                    'photo' => $filePath,
                ]);
            }
            
    
            return response([
                'response' => 'success'
            ], 200);
    
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error($e->getMessage());
            return response([
                'error' => 'An error occurred'
            ], 500);
        }

    }

    public function login_users(Request $request){
            // Validate input fields
            $fields = $request->validate([
                'email' => 'required|email|exists:users',
                'password' => 'required',
            ]);
        
            // Attempt to find the user by email
            $user = User::where('email', $fields['email'])->first();
        
            // Check if the user exists and if the provided password matches
            if (!$user || !Hash::check($fields['password'], $user->password)) {
                return response()->json([
                    'message' => 'The provided credentials are incorrect.'
                ], 401); // 401 Unauthorized
            }
            // Generate a token for the user
            $token = $user->createToken($request->email);
            // Return the user and token as a JSON response
            return response()->json([
                'user' => $user,
                'token' => $token->plainTextToken
            ], 200); // 200 OK
    }

    public function client_file() {
        {

            return response([
                'File' => File::select('*')->get()
            ], 200);
       }
    }
}
