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
            'data' => 'required|array',
            'data.*.create_id' => 'required|exists:tbl_created_task,create_id', // Assuming 'creates' is the table name
            'data.*.Office_name' => 'required|string',
            'data.*.Office_task' => 'required|string',
            'data.*.New_alloted_time' => 'required|string',
        ]);

        foreach ($request->data as $record) {
            $data = Create::findOrFail($record['create_id']);
            $data->update([
                'Office_name' => $record['Office_name'],
                'Office_task' => $record['Office_task'],
                'New_alloted_time' => $record['New_alloted_time'],
            ]);
        }

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
        
        $record = Task::findOrFail($id);
        $record->update([
            'soft_del' => 1,
        ]);

        // Return the admin dashboard view with the data and user count
        return redirect()->back()->with('success', 'Task has been Deactivated.');
                         
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

    public function task_activate($id){

        $activate = Task::findOrFail($id);
        $activate->update([
            'status' => 1,
        ]);

        // Return the admin dashboard view with the data and user count
        return redirect()->back()->with('success', 'Task is successfully Activated.');
    }
}
