<?php

namespace App\Http\Controllers;

use App\Events\AdminCreateHoliday;
use Illuminate\Http\Request;
use App\Models\Create;
use App\Models\Supplier;
use App\Models\Holiday;
use App\Models\User;
use App\Models\Transaction;
use App\Models\NewOffice;
use App\Models\Task;
use Illuminate\Support\Facades\Storage;
use App\Events\AdminCreteTask;
use App\Events\AdminGetEdit;
use App\Events\AdminGetEdited;

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
            'filepath' => 'required|file|mimes:docx,doc,xlsx,xls|max:10240', // Include doc and docx
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
            'status' => 1,
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
                
                // Process time input
                $timeInMinutes = 0; // Default to 0 minutes
                preg_match('/(\d+)\s*(minute|hour|day|week)s?/', $timeForOffice, $matches);

                if (count($matches) === 3) {
                    $number = (int)$matches[1]; // Get the number
                    $unit = strtolower($matches[2]); // Get the unit (minute, hour, day, week)

                    // Convert to minutes based on the unit
                    switch ($unit) {
                        case 'minute':
                            $timeInMinutes = $number;
                            break;
                        case 'hour':
                            $timeInMinutes = $number * 60;
                            break;
                        case 'day':
                            $timeInMinutes = $number * 60 * 24;
                            break;
                        case 'week':
                            $timeInMinutes = $number * 60 * 24 * 7;
                            break;
                    }
                }
                // Save the task details to the 'Create' model
                Create::create([
                    'Office_name' => $office_name,
                    'Office_task' => $taskForOffice,
                    'New_alloted_time' => $timeInMinutes,
                    'user_id' => $user->user_id,
                    'task_id' => $taskId, 
                    'soft_del' => 0,
                ]);

                event( new AdminCreteTask($user));
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

        foreach ($data as $item) {
            if (is_string($item->New_alloted_time)) {
                $newAllotedTimeInMinutes = (int)$item->New_alloted_time;
        
                // Check if the time is less than or equal to 59 minutes
                if ($newAllotedTimeInMinutes <= 59) {
                    $item->New_alloted_time_display = "{$newAllotedTimeInMinutes} minutes";
                } 
                // Check if the time is less than or equal to 1380 minutes (23 hours)
                else if ($newAllotedTimeInMinutes <= 1380) {
                    $item->New_alloted_time_display = round($newAllotedTimeInMinutes / 60, 2) . " hours";
                }// Check if the time is less than or equal to 43200 minutes (30 days)
                else if ($newAllotedTimeInMinutes <= 43200) {
                    $item->New_alloted_time_display = round($newAllotedTimeInMinutes / 1440, 2) . " days";
                }// Check if the time is less than or equal to 524160 minutes (52 weeks)
                else if ($newAllotedTimeInMinutes <= 524160) {
                    $item->New_alloted_time_display = round($newAllotedTimeInMinutes / 10080, 2) . " weeks";
                } else {
                    // Handle other cases if needed, e.g., days, weeks
                    $item->New_alloted_time_display = "{$newAllotedTimeInMinutes} minutes";
                }
            } else {
                // Fallback if New_alloted_time is not a string
                $item->New_alloted_time_display = "Invalid time format";
            }
        }
        

        //app-bar
        $admin = User::select('firstname','lastname','middlename','user_id')->where('account_type','Admin')->first();
        $UserId = session('user_id');
        $user = User::find($UserId);
        event(new AdminGetEdit($user));
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

                // Process time input
                $timeInMinute = 0; // Default to 0 minutes
                preg_match('/(\d+)\s*(minute|hour|day|week)s?/', $record['New_alloted_time'], $matches);

                if (count($matches) === 3) {
                    $number = (int)$matches[1]; // Get the number
                    $unit = strtolower($matches[2]); // Get the unit (minute, hour, day, week)

                    // Convert to minutes based on the unit
                    switch ($unit) {
                        case 'minute':
                            $timeInMinute = $number;
                            break;
                        case 'hour':
                            $timeInMinute = $number * 60;
                            break;
                        case 'day':
                            $timeInMinute = $number * 60 * 24;
                            break;
                        case 'week':
                            $timeInMinute = $number * 60 * 24 * 7;
                            break;
                    }
                }

                $data = Create::findOrFail($record['create_id']);
                $data->update([
                    'Office_name' => $record['Office_name'],
                    'Office_task' => $record['Office_task'],
                    'New_alloted_time' => $timeInMinute,
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

                // Process time input
                $timeInMinutes = 0; // Default to 0 minutes
                preg_match('/(\d+)\s*(minute|hour|day|week)s?/', $times, $matches);

                if (count($matches) === 3) {
                    $number = (int)$matches[1]; // Get the number
                    $unit = strtolower($matches[2]); // Get the unit (minute, hour, day, week)

                    // Convert to minutes based on the unit
                    switch ($unit) {
                        case 'minute':
                            $timeInMinutes = $number;
                            break;
                        case 'hour':
                            $timeInMinutes = $number * 60;
                            break;
                        case 'day':
                            $timeInMinutes = $number * 60 * 24;
                            break;
                        case 'week':
                            $timeInMinutes = $number * 60 * 24 * 7;
                            break;
                    }
                }

                // Group the data and save it to the database
                Create::create([
                    'Office_name' => $office_name,
                    'Office_task' => $tasks,
                    'New_alloted_time' => $timeInMinutes,
                    'user_id' => $user->user_id,
                    'task_id' => $id,
                    'soft_del' => 0,
                ]);
            }
        }

        $UserId = session('user_id');
        $user = User::find($UserId);
        event(new AdminGetEdited($user));
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


    public function holiday(Request $request){
        $attrs = $request->validate([
            'desc' => 'required', 
            'date' => 'required', 
        ]);

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $attrs['date'])){
            return redirect()->back()->with('error', 'Date is not yyyy/dd/mm.');
        } else{
            Holiday::create([
                'description' => $attrs['desc'],
                'holiday_date' => $attrs['date'],
            ]);
            $UserId = session('user_id');
            $user = User::find($UserId);
            event (new AdminCreateHoliday($user));
            return redirect()->back()->with('success', 'Success.');
        }
    }

}
