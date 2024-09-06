<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator; 
use App\Models\User;
use App\Models\File;
use Illuminate\Support\Facades\Password;

class ApiController extends Controller
{
   public function index()
    {
        return user::all();

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
            return [
                'user' => $user,
                'token' => $token->plainTextToken
            ];
        }
    }


    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required',
        ]);
    
        $user = User::where('email', $fields['email'])->first();
    
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.'
            ], 401); 
        }
        // Generate a token for the user
        $token = $user->createToken($request->email);

        return response()->json([
            'user' => $user,
            'token' => $token->plainTextToken
        ], 200); 
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return[
            'message' => 'Logged out'
        ];
    }
    
    public function client_file() {
        {

            return response([
                'File' => File::select('*')->get()
            ], 200);
       }
    }
}
