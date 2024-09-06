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

    public function uploaded_files() {
        $files = File::all();
    
        foreach ($files as $file) {
            $filePath = storage_path('app/' . $file->filepath);
            
            if (in_array($file->type, ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])) {
                try {
                    // Load the Word document
                    $phpWord = IOFactory::load($filePath);
                    
                    // Convert the document to HTML
                    $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');
                    ob_start();
                    $htmlWriter->save('php://output');
                    $htmlContent = ob_get_clean();
                    
                    // Strip unwanted tags
                    $htmlContent = preg_replace('/<head>.*<\/head>/s', '', $htmlContent);
                    $htmlContent = preg_replace('/<style>.*<\/style>/s', '', $htmlContent);
                    $htmlContent = preg_replace('/<!DOCTYPE html.*?>/', '', $htmlContent);
                    $htmlContent = preg_replace('/<html.*?>|<\/html>|<body.*?>|<\/body>/s', '', $htmlContent);
                    
                    $file->htmlContent = $htmlContent;
                } catch (\Exception $e) {
                    Log::error('Error processing Word document: ' . $e->getMessage());
                    $file->htmlContent = '<p>Could not display document. Error: ' . $e->getMessage() . '</p>';
                }
            } elseif ($file->type == 'application/pdf') {
                $file->pdfUrl = Storage::url($file->filepath);
            } else {
                $file->htmlContent = null;
                $file->pdfUrl = null;
            }
        }
    
        return view('admin.uploaded_files', compact('files'));
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
            return redirect()->route('admin.uploaded_files')->with('success', 'File uploaded successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to upload file: Something went wrong ');// . $e->getMessage()
        }
    }
    
}
