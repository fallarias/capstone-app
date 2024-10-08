<?php

namespace App\Http\Controllers;


use App\Models\Supplier;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Task;
use App\Models\Logs;

class DashboardController extends Controller
{
    public function dashboard(){

        $supplier = Supplier::count(); //suppliers not create task
        $user = User::whereIn('account_type', ['client', 'office staff', 'supplier'])->count();
        $transaction = Transaction::count();
        $activate = Task::where("status", 1)->where('soft_del', 0)->count();
        $users = User::all();
        // Return the admin dashboard view with the data and user count
        return view('admin.dashboard', compact('supplier','user', 'activate','transaction', 'users'));
    }

    public function logs(){
        $logs = Logs::all();

        return view('admin.logsPage', compact('logs'));
    }
    public function getStats() {
        $supplier = Supplier::count();
        $user = User::whereIn('account_type', ['client', 'office staff', 'supplier'])->count();
        $transaction = Transaction::count();
        $activate = Task::where("status", 1)->where('soft_del', 0)->count();

        // Return the counts as JSON for AJAX requests
        return response()->json([
            'supplier' => $supplier,
            'user' => $user,
            'transaction' => $transaction,
            'activate' => $activate,
        ]);
    }

}
