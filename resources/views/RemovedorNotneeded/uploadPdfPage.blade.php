<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>


</head>
<body>
        <!-- App Bar -->
        @include('components.app-bar')
        
        @if (session('success'))
            <p>{{ session('success') }}</p>
        @endif
        @if (session('error'))
            <p>{{ session('error') }}</p>
        @endif
        <div class="main-content">
    <h1 style="font-size: 40px; margin-bottom: -10px">
        <i class="fas fa-file-alt" style="font-size: 40px; margin-bottom: -20px;"></i> Upload Files
    </h1>
    <p style="color: dimgray">Uploaded project attachments.</p>

    <!-- Transparent green box -->
    <div class="green-box">
        <div class="checkbox-container">
            <label class="checkbox-label" style="margin-left:-1035px">
                <input type="checkbox" id="checkbox1"> <span style="margin-top: -18px;">Upload File</span> </input>
            </label>
        </div>
        <div id="form1" class="hidden">
            <form action="{{ route('admin.upload_file') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf
                
                <!-- Instruction Text Above Drop Zone -->
                <div class="upload-instructions">
                    <i class="fas fa-file" style="font-size: 70px; color: #333; margin-left:500px; margin-top:40px;"></i><br>
                    <label for="" style="font-size: 30px; margin-left:279px; text-align: center;">Drag & drop your file into the box below</label><br>
                    <i class="fas fa-arrow-down" style="padding-top: 20px; font-size: 50px; text-align: center; margin-left:510px;"></i><br>
                </div>

                <!-- Drag & Drop Zone Positioned at the Bottom -->
            <!-- Drag & Drop Zone Positioned at the Bottom -->

            <div id="dropzone" class="dropzone-box" onclick="document.getElementById('fileInput').click()">
                <i id="dropzoneIcon" class="fas fa-plus-circle" style="font-size: 70px; color: #333;"></i><br>
                <label id="dropzoneLabel" style="font-size: 20px;">You can also select the files</label>
                <input type="file" id="fileInput" name="filepath[]" multiple accept=".jpg, .png, .pdf, .zip, .doc, .docx" style="display: none;" />
                <div id="fileDisplay" class="file-display"></div>
            </div>
                <input type="submit" value="Upload Files" class="upload-btn" style="margin-top: 20px; margin-left: 420px;">
            </form>
        </div>
    </div>

        <p style="color: dimgray">Only .jpg and png files 500kb max file size.</p>

        <h1 style="font-size: 40px; margin-top: 50px">
            <i style="font-size: 40px; margin-bottom: -20px;"></i> Upload Files
        </h1>
    </div>


    <div style="display: flex; justify-content: center; margin-top: 0px; width: 1000px; margin-left: 400px;">
        <div class="red-box">
            <!-- FILE DISPLAY -->
            <button id="viewAllBtn" style="margin-top: 10px; cursor: pointer;">View All</button>
            <button id="viewLessBtn" style="margin-top: 10px; cursor: pointer; display: none;">View Less</button>
            <div id="form2">
                @if($files->isEmpty())
                    <p>No files uploaded.</p>
                @else
                    <div id="fileList">
                        @foreach($files as $file)
                            <div class="file-entry" style="position: relative; border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; display: flex; align-items: center; width:1150px; @if($loop->index >= 3) display: none; @endif">
                                <div class="delete-icon" style="position: absolute; top: 10px; right: 10px; cursor: pointer;">
                                    <form action="{{ route('admin.pdfFileDelete', $file->file_id) }}" method="POST">
                                        @csrf
                                        @method('DELETE') <!-- Ensure this is a DELETE request -->
                                        <button type="submit" class="fas fa-trash-alt" style="font-size:40px; margin-top:20px; color:red;border:0"></button>
                                    </form>
                                </div>
                                <div class="file-icon" style="margin-right: 20px;">
                                    @if($file->type == 'application/pdf')
                                        <a href="{{ $file->pdfUrl }}" target="_blank">
                                            <i class="fas fa-file-pdf" style="font-size: 70px; cursor: pointer;"></i></a>
                                    @else
                                        <p>Not an PDF file.</p>
                                    @endif
                                </div>
                                <div class="file-details">
                                    <p><strong>Filename:</strong> {{ $file->filename }}</p>
                                    <p><strong>File Size:</strong> {{ $file->size }} bytes</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
    @include('components.uploadPdf')
</body>
</html>
