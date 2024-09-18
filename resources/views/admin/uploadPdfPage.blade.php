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


    <!--SCRIPT FOR VIEW ALL BUTTON TO  SHOW ALL FILES-->
    <script>
        document.getElementById('viewAllBtn').addEventListener('click', function() {
            // Show all files
            document.querySelectorAll('#fileList .file-entry').forEach(function(fileEntry, index) {
                if (index >= 3) {
                    fileEntry.style.display = 'flex';
                }
            });
            // Hide the 'View All' button and show 'View Less' button
            this.style.display = 'none';
            document.getElementById('viewLessBtn').style.display = 'block';
        });

        document.getElementById('viewLessBtn').addEventListener('click', function() {
            // Hide extra files
            document.querySelectorAll('#fileList .file-entry').forEach(function(fileEntry, index) {
                if (index >= 3) {
                    fileEntry.style.display = 'none';
                }
            });
            // Show the 'View All' button and hide 'View Less' button
            this.style.display = 'none';
            document.getElementById('viewAllBtn').style.display = 'block';
        });

        // Initially hide all except the first 3 files
        let fileEntries = document.querySelectorAll('#fileList .file-entry');
        if (fileEntries.length > 3) {
            for (let i = 3; i < fileEntries.length; i++) {
                fileEntries[i].style.display = 'none';
            }
        }
    </script>


    





    <!-- SCRIPT FOR THE CHECK BOX UPLOAD -->
    <script>
        function updateFileName(input) {
            const fileName = input.files[0] ? input.files[0].name : "Select File";
            document.querySelector('.custom-file-upload').textContent = fileName || 'Select File';
        }

        document.querySelectorAll('.checkbox-label input').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                // Get the form associated with the checkbox
                const formId = this.id === 'checkbox1' ? 'form1' : 'form2';
                const formToShow = document.getElementById(formId);
                
                // Uncheck the other checkbox and hide the other form
                document.querySelectorAll('.checkbox-label input').forEach(function(otherCheckbox) {
                    if (otherCheckbox !== checkbox) {
                        otherCheckbox.checked = false; // Uncheck other checkboxes
                        document.getElementById(otherCheckbox.id === 'checkbox1' ? 'form1' : 'form2').classList.add('hidden'); // Hide other form
                    }
                });

                // Show the current form if checked, otherwise hide it
                if (this.checked) {
                    formToShow.classList.remove('hidden');
                } else {
                    formToShow.classList.add('hidden');
                }
            });
        });
    </script>




    <!-- SCRIPT FOR DISPLAYING NAME OF FILE AT THE BOX -->
    <script>
        const fileInput = document.getElementById('fileInput');
        const fileDisplay = document.getElementById('fileDisplay');
        const dropzoneIcon = document.getElementById('dropzoneIcon');
        const dropzoneLabel = document.getElementById('dropzoneLabel');

        let filesArray = [];

        // Function to update file display
        function updateFileDisplay() {
            fileDisplay.innerHTML = ''; // Clear the current display

            if (filesArray.length > 0) {
                // Hide the icon and label
                dropzoneIcon.style.display = 'none';
                dropzoneLabel.style.display = 'none';

                // Display file names with remove buttons
                filesArray.forEach((file, index) => {
        const fileEntry = document.createElement('div');
        fileEntry.className = 'file-entry';
        fileEntry.innerHTML = `
            <div class="files-box">
            <span class="file-name">${file.name}</span>
            <button type="button" class="remove-file" data-index="${index}">&times;</button></div>
        `;
        fileDisplay.appendChild(fileEntry);
    });


            } else {
                // If no files are selected, show default message
                fileDisplay.innerHTML = 'No file selected';
                dropzoneIcon.style.display = 'block';
                dropzoneLabel.style.display = 'block';
            }
        }

        // Handle file selection
        fileInput.addEventListener('change', function() {
            const newFiles = Array.from(fileInput.files);
            filesArray = [...filesArray, ...newFiles];
            updateFileDisplay();
        });

        // Handle file drop
        const dropzone = document.getElementById('dropzone');
        dropzone.addEventListener('drop', function(e) {
            e.preventDefault(); // Prevent default behavior

            const dt = e.dataTransfer;
            const droppedFiles = Array.from(dt.files);

            filesArray = [...filesArray, ...droppedFiles];
            updateFileDisplay();
        });

        // Handle file removal
        fileDisplay.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-file')) {
                const index = e.target.getAttribute('data-index');
                filesArray.splice(index, 1);
                updateFileDisplay();
            }
        });

        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        // Handle form submission
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            const formData = new FormData();

            // Append files from filesArray
            filesArray.forEach(file => {
                formData.append('filepath[]', file);
            });

            // Submit the form using fetch or XMLHttpRequest
            fetch('{{ route('admin.upload_file') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(response => {
                // Handle the response
                if (response.ok) {
                    // Do something on success, like reloading the page or showing a success message
                    window.location.reload();
                } else {
                    // Handle errors
                    console.error('Error uploading files');
                }
            }).catch(error => {
                console.error('Error:', error);
            });
        });
    </script>



    <!--SCRIPT FOR DDRAG AND DROP UPLOAD -->
    <script>
        const dropzone = document.getElementById('form1');
        const fileInput = document.getElementById('fileInput');
        const dropzoneIcon = document.getElementById('dropzoneIcon');
        const dropzoneLabel = document.getElementById('dropzoneLabel');

        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        // Highlight drop area when item is dragged over it
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, unhighlight, false);
        });

        // Handle dropped files
        dropzone.addEventListener('drop', handleDrop, false);

        // Handle file selection
        fileInput.addEventListener('change', handleFiles, false);

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        function highlight() {
            dropzone.classList.add('highlight');
        }

        function unhighlight() {
            dropzone.classList.remove('highlight');
        }

        function handleDrop(e) {
            preventDefaults(e);  // Prevent default behavior

            const dt = e.dataTransfer;
            const files = dt.files;

            // Manually link the dropped files with the file input
            fileInput.files = files;

            handleFiles(files);
        }

        function handleFiles(files) {
            const fileArray = Array.from(files);
            if (fileArray.length > 0) {
                // Hide the icon and label after files are dropped
                dropzoneIcon.style.display = 'none';
                dropzoneLabel.style.display = 'none';
                
                // Optionally, display the names of the uploaded files
                const fileDisplay = document.getElementById('fileDisplay');
                fileDisplay.innerHTML = fileArray.map(file => file.name).join('<br>');
            }
        }
    </script>



</body>
</html>
