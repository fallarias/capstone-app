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