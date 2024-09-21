<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Transaction;
use App\Models\NewOffice;
use App\Models\Task;
use Illuminate\Support\Facades\Storage;

class GetRouteController extends Controller
{
    public function create(){
        
        $offices = NewOffice::all();
        return view('admin.createTaskPage', compact('offices'));

    }
    public function list() {
        // Get tasks with their associated files
        $data = Task::with('files')->get();

        // Iterate through the files and prepare URLs for PDFs
        foreach ($data as $task) {
            foreach ($task->files as $file) {
                if ($file->type == 'application/pdf') {
                    $file->pdfUrl = Storage::url($file->filepath); // Generate URL for the PDF file

                    // Remove the .pdf extension from the file name
                    $file->filename = pathinfo($file->filename, PATHINFO_FILENAME);

                } else {
                    $file->pdfUrl = null;
                }
            }
        }

        return view('admin.listOfTaskPage', compact('data'));
    }
    public function supplier(){

        $supplier = Supplier::all();

        return view('admin.supplierListPage', compact('supplier'));

    }
    public function activated_task(){
        // Get tasks with their associated files
        $data = Task::with('files')->where('soft_del', '0')->where('status','=',1)->get();

        // Iterate through the files and prepare URLs for PDFs
        foreach ($data as $task) {
            foreach ($task->files as $file) {
                if ($file->type == 'application/pdf') {
                    $file->pdfUrl = Storage::url($file->filepath); // Generate URL for the PDF file
                } else {
                    $file->pdfUrl = null;
                }
            }
        }
        return view('admin.activatedTaskListPage', compact('data'));

    }

    public function transaction(){

        $transaction = Transaction::all();
        return view('admin.transactionListPage', compact('transaction'));

    }
    public function user(){
        $user = User::all();

        return view('admin.allUserProfile', compact('user'));
    }
}
