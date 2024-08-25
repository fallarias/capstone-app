<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class signupController extends Controller
{

    public function index(){
        return view('admin.signup');
    }
    
    public function submit(Request $request)
    {
        // Validate and handle the form data
        $attrs = $request->validate([
            'password' => 'required',
            'email' => 'required',
            'account_type' => 'required',
        ]);
        
        User::create([
            'password' => $attrs['password'],
            'email' => $attrs['email'],
            'account_type' => $attrs['account_type']
         ]);
       // Auth:login($user);

        return view('admin.login');
    }
    
}
