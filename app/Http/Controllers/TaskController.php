<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Create;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Transaction;
use App\Models\NewOffice;
use App\Models\Task;
use Illuminate\Support\Facades\Storage;


class TaskController extends Controller
{

    public function createOfficeTask(Request $request){        

        // Validate request
        $attrs = $request->validate([
            'office_name' => 'required|array|min:1',
            'office_name.*' => 'required|string',
            'task' => 'required|array|min:1',
            'task.*' => 'required|string',
            'time' => 'required|array|min:1',
            'time.*' => 'required|string',
            'task_name'=> 'required|string|unique:task,name',
            'filepath' => 'required|file|mimes:pdf|max:10240', // Include doc and docx
        ]);
    
        // Handle file upload first
        $filePath = null; // Initialize filepath
        if ($request->hasFile('filepath')) {
            $file = $request->file('filepath');
            $filePath = $file->store('public');
        }
    
        // Create the task with the file information
        $taskName = Task::create([
            'name' => $attrs['task_name'],
            'filename' => $file->getClientOriginalName(),
            'filepath' => $filePath,
            'size' => $file->getSize(),
            'type' => $file->getClientMimeType(),
        ]);
    
        // Get the task ID directly 
        $taskId = $taskName->task_id;
    
        $user = User::where('account_type', 'Admin')->first();
    
        if(!empty($attrs['office_name']) || !empty($attrs['task']) || !empty($attrs['time'])|| !empty($attrs['task_name'])){ 
    
            $office_names = $request->input('office_name');
            $tasks = $request->input('task'); 
            $time = $request->input('time');
    
            // Iterate over the arrays and group data based on index
            foreach ($office_names as $index => $office_name) {
                // Retrieve corresponding task and time for each office
                $taskForOffice = $tasks[$index];
                $timeForOffice = $time[$index];
    
                // Save the task details to the 'Create' model
                Create::create([
                    'Office_name' => $office_name,
                    'Office_task' => $taskForOffice,
                    'New_alloted_time' => $timeForOffice,
                    'user_id' => $user->user_id,
                    'task_id' => $taskId, 
                    'soft_del' => 0,
                ]);
            }
            
            return redirect()->back()->with('success', 'Task is successfully added.');
        }
    }
    

    public function edit($id){
        $offices = User::select('department')
                        ->where('account_type','office staff')
                        ->distinct()
                        ->get();
                        
        $task = Task::findOrFail($id);
        if($task){
            $data_task = Task::all()->where('task_id', '=', $id)->first();
        }
        $data = Create::all()->where('task_id',$id);
        //app-bar
        $admin = User::select('firstname','lastname','middlename')->where('account_type','Admin')->first();
        return view('admin.editTaskPage', compact('data',"task","data_task","offices",'admin'));

    }

    public function update(Request $request, $id)
    {
        $attrs = $request->validate([
            'task_name' =>'required',
            'data' => 'required|array',
            'data.*.create_id' => 'required|exists:tbl_created_task,create_id', // Assuming 'creates' is the table name
            'data.*.Office_name' => 'required|string',
            'data.*.Office_task' => 'required|string',
            'data.*.New_alloted_time' => 'required|string',
            'filepath' => 'nullable|file|mimes:pdf|max:10240', // Include doc and docx
            // adding step of task
            'office_name' => 'nullable|array|min:1',
            'office_name.*' => 'nullable|string',
            'task' => 'nullable|array|min:1',
            'task.*' => 'nullable|string',
            'time' => 'nullable|array|min:1',
            'time.*' => 'nullable|string',
        ]);

        $task = Task::findOrFail($id);
        // Handle file upload
        if ($request->hasFile('filepath')) {
            $file = $request->file('filepath'); // Get the uploaded file

            // Ensure the filepath is not null before checking if it exists
            if (!empty($task->filepath) && Storage::exists($task->filepath)) {
                Storage::delete($task->filepath);
            }

            // Store the file
            $filePath = $file->store('public');

            // Update the task with the new file information
            $task->update([
                'filename' => $file->getClientOriginalName(),
                'filepath' => $filePath,
                'size' => $file->getSize(),
                'type' => $file->getClientMimeType(),
            ]);
        }

        // Update task name
        $task->update([
            'name' => $attrs['task_name'],
        ]);

        // Check if the 'data' field exists and is an array before looping
        if (!empty($request->data) && is_array($request->data)) {
            foreach ($request->data as $record) {
                $data = Create::findOrFail($record['create_id']);
                $data->update([
                    'Office_name' => $record['Office_name'],
                    'Office_task' => $record['Office_task'],
                    'New_alloted_time' => $record['New_alloted_time'],
                ]);
            }
        }

        $user = User::where('account_type', 'Admin')->first();
        $office_names = $request->input('office_name');
        $task = $request->input('task'); // Retrieves an array of office names
        $time = $request->input('time');

        // Handle the case where 'data' doesn't exist or you need to create new records
        if (!empty($office_names) && is_array($office_names)) {
            foreach ($office_names as $index => $office_name) {
                // Retrieve corresponding task and time for each office
                $tasks = $task[$index];
                $times = $time[$index];

                // Group the data and save it to the database
                Create::create([
                    'Office_name' => $office_name,
                    'Office_task' => $tasks,
                    'New_alloted_time' => $times,
                    'user_id' => $user->user_id,
                    'task_id' => $id,
                    'soft_del' => 0,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Task is successfully edited.');

    }
    public function delete_task($id){
        
        $record = Task::findOrFail($id);
        $record->update([
            'status' => 0,
        ]);

        // Return the admin dashboard view with the data and user count
        return redirect()->back()->with('success', 'Task has been Deactivated.');
                         
    }

    public function task_activate($id){

        $activate = Task::findOrFail($id);
        $activate->update([
            'status' => 1,
        ]);

        // Return the admin dashboard view with the data and user count
        return redirect()->back()->with('success', 'Task is successfully Activated.');
    }


    public function add_office(Request $request) {

        $attrs = $request->validate([
            'office_name' => 'required|unique:offices,office_name', 
        ]);

        NewOffice::create([
            'office_name' => $attrs['office_name'],
        ]);

        return redirect()->back()->with('success_office', 'Office is successfully added.');
    }

}
