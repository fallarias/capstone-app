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
use Illuminate\Support\Facades\Storage;
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
        $files = Task::all();
    
        foreach ($files as $file) {
            if ($file->type == 'application/pdf') {
                // Use Storage::url() to get the URL
                $file->pdfUrl = url(Storage::url($file->filepath));
                
                // Remove the .pdf extension from the file name
                $file->filename = pathinfo($file->filename, PATHINFO_FILENAME);
                
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
    

    
    
}
