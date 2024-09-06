<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator; 
use App\Models\User;
use App\Models\File;

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
        
        $attrs = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        if(!Auth::attempt($attrs)){
            return Response([
                'message' => 'Invalid credentials.'
            ], 403);
        }

        return response([
            'user' => auth()->user(),
            'token' => auth()->user()->createToken('secret')->plainTextToken
        ], 200);
        
    }
    
    public function client_file() {
        {

            return response([
                'File' => File::select('*')->get()
            ], 200);
       }
    }
}
