<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Create;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Client;
use App\Models\Request as ModelsRequest;
use App\Models\qrcode;
use App\Models\Task;
use Carbon\Carbon;

class TaskController extends Controller
{
    
    public function create(){

        return view('admin.createTaskPage');

    }


    public function createOfficeTask(Request $request){

        $attrs = $request->validate([
            'office_name' => 'required|array|min:1',
            'office_name.*' => 'required|string',
            'task' => 'required|array|min:1',
            'task.*' => 'required|string',
            'time' => 'required|array|min:1',
            'time.*' => 'required|string',
            'task_name'=> 'required|string',
        ]);
        

        // Check if the task_name already exists
        $taskName = Task::Create(['name' => $attrs['task_name']]);

        // Get the task ID directly 
        $taskId = $taskName->task_id;

        $user = User::where('account_type', 'Admin')->first();

        if(!empty($attrs['office_name']) || !empty($attrs['task']) || !empty($attrs['time'])|| !empty($attrs['task_name'])){ 

            $office_names = $request->input('office_name');
            $task = $request->input('task'); // Retrieves an array of office names
            $time = $request->input('time');

            // Iterate over the arrays and group data based on index
            foreach ($office_names as $index => $office_name) {
                // Retrieve corresponding task and time for each office
                $tasks = $task[$index];
                $times = $time[$index];

                // Now you can group the data and process it (e.g., save it to the database)
                Create::create([
                    'Office_name' => $office_name,
                    'Office_task' => $tasks,
                    'New_alloted_time' => $times,
                    'user_id' => $user->user_id,
                    'task_id' => $taskId, 
                    'soft_del' => 0,
                ]);
            }
            
            // Return the admin dashboard view with the data and user count
            return redirect()->back()->with('success', 'Task is successfully added.');

        }
        
    }

    public function list(){

        $data = Task::all()->where('soft_del','=','0');
        return view('admin.listOfTaskPage', compact('data'));

    }



    public function edit($id){

        $task = Task::findOrFail($id);
        if($task){
            $data_task = Task::all()->where('task_id', '=', $id)->first();
        }
        $data = Create::all()->where('task_id',$id);
        return view('admin.editTaskPage', compact('data',"task","data_task"));

    }

    public function update(Request $request, $id)
    {
        $attrs = $request->validate([
            'Office_name' => 'required',
            'Office_task' => 'required',
            'New_alloted_time' => 'required',
        ]);

        // Find the record by ID
        $record = Create::findOrFail($id);

        // Update the record
        $record->update([
            'Office_name' => $attrs['Office_name'],
            'Office_task' => $attrs['Office_task'],
            'New_alloted_time' => $attrs['New_alloted_time'],
        ]);

        // Redirect to a specific page or view
        $supplier = Supplier::count(); //suppliers not create task
        $user = User::count(); 
        $transaction = Transaction::count();
        $client = Client::count();
        $users = User::all();
        // Return the admin dashboard view with the data and user count
        return view('admin.dashboard', compact('supplier','user', 'client','transaction', 'users'));

    }
    public function delete_task($id){
        
        $record = Create::findOrFail($id);
        $record->update([
            'soft_del' => 1,
        ]);
        $supplier = Supplier::count(); //suppliers not create task
        $user = User::count(); 
        $transaction = Transaction::count();
        $client = Client::count();
        $users = User::all();
        // Return the admin dashboard view with the data and user count
        return view('admin.dashboard', compact('supplier','user', 'client','transaction', 'users'));
                         
    }

    public function supplier(){

        $supplier = Supplier::all();

        return view('admin.supplierListPage', compact('supplier'));

    }
    public function clients(){
        $clients = Client::all();

        return view('admin.clientListPage', compact('clients'));

    }

    public function transaction(){

        $transaction = Transaction::all();
        return view('admin.transactionListPage', compact('transaction'));

    }

    public function user(){
        $user = User::all();

        return view('admin.allUserProfile', compact('user'));
    }

    public function request(){
        $data = [
            'labels' => Carbon::now()->subMonths()->format('F'),
            'data' => [65, 59, 80, 81],
        ];
        $request = ModelsRequest::all();

        return view('admin.request', compact('request','data'));
    }

    public function qrcode(){
        $qrcode = qrcode::all();

        return view('admin.qrcodePage', compact('qrcode'));
    }
}
