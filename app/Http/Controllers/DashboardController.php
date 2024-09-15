<?php

namespace App\Http\Controllers;


use App\Models\Supplier;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Client;

class DashboardController extends Controller
{
    public function dashboard(){

        $supplier = Supplier::count(); //suppliers not create task
        $user = User::count(); 
        $transaction = Transaction::count();
        $client = Client::count();
        $users = User::all();
        // Return the admin dashboard view with the data and user count
        return view('admin.dashboard', compact('supplier','user', 'client','transaction', 'users'));
    }
    

}
