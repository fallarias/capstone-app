<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Http\Request;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
class FileController extends Controller
{


    public function upload() {
        $files = File::all();
    
        foreach ($files as $file) {
            $filePath = storage_path('app/' . $file->filepath);
            
            if ($file->type == 'application/pdf') {
                $file->pdfUrl = Storage::url($file->filepath);
            } else {
                $file->htmlContent = null;
                $file->pdfUrl = null;
            }
        }
    
        return view('admin.uploadPdfPage', compact('files'));
    }
    

    public function upload_pdf(Request $request){

        $attrs = $request->validate([
            'filepath.*' => 'required|file|mimes:jpeg,jpg,png,pdf,zip,doc,docx|max:10240', // Include doc and docx
        ]);
        
        try {
            foreach ($request->file('filepath') as $file) {
                $filePath = $file->store('public');
                File::create([
                    'filename' => $file->getClientOriginalName(),
                    'filepath' => $filePath,
                    'size' => $file->getSize(),
                    'type' => $file->getClientMimeType(),
                ]);
            }


            return redirect()->back()->with('success', 'File is successfully uploaded.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to upload file: Something went wrong ');// . $e->getMessage()
        }
    }



    public function delete_pdf($file){

        $filePath = File::findOrFail($file);
        $filePath->delete();
        return redirect()->back()->with('success', 'File is successfully uploaded.');


    }
    
}
