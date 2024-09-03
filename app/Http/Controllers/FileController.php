<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Http\Request;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
class FileController extends Controller
{
    public function upload(){
        return view('admin.upload');
    }

    public function uploaded_files(){

    $files = File::all();

    foreach ($files as $file) {
        if (in_array($file->type, ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])) {
            $filePath = storage_path('app/' . $file->filepath);

            try {
                // Load the Word document
                $phpWord = IOFactory::load($filePath);

                // Convert the document to HTML
                $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');
                $htmlContent = '';

                ob_start();
                $htmlWriter->save('php://output');
                $file->htmlContent = ob_get_contents(); // Store the HTML content in the file object
                ob_end_clean();
            } catch (\Exception $e) {
                // Log the error or handle it as needed
                Log::error('Error processing Word document: ' . $e->getMessage());
                $file->htmlContent = '<p>Could not display document. Error: ' . $e->getMessage() . '</p>';
            }
        } else {
            $file->htmlContent = null; // Non-Word documents won't have HTML content
        }
    }

    return view('admin.uploaded_files', compact('files'),['htmlContent' => $htmlContent]);

    }

    public function upload_files(Request $request){

        $attrs = $request->validate([
            'filepath.*' => 'required|file|mimes:jpeg,jpg,png,pdf,zip,doc,docx|max:10240', // Include doc and docx
        ]);
        
        try {
            foreach ($request->file('filepath') as $file) {
                $filePath = $file->store('public');
                File::create([
                    'filename' => time() . '_' . $file->getClientOriginalName(),
                    'filepath' => $filePath,
                    'size' => $file->getSize(),
                    'type' => $file->getClientMimeType(),
                ]);
            }
            return redirect()->back()->with('success', 'File uploaded successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to upload file: ' . $e->getMessage());
        }
    }
    
}
