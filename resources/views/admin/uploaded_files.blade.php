<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uploaded Files</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<style>
    a {
        text-decoration: none;
        color: #007bff; /* Link color */
        display: flex;
        align-items: center;
    }

    a i {
        margin-right: 8px; /* Space between icon and text */
    }
    i {
        font-size: 100px;
    }
    
    /* Modal-specific styles */
    .modal-dialog-fullscreen {
        max-width: 100vw;
        height: 100vh;
        margin: 0;
    }

    .modal-content {
        height: 100%;
        border-radius: 0;
    }

    .modal .small-word-content {
        font-size: 12px !important; /* Increase font size inside modal */
        line-height: 1.5 !important; /* Adjust line height for readability */
        max-width: 100%; /* Allow content to use the full width */
    }
    .small-word-content {
    font-size: 12px;
    margin: 0;
    word-spacing: 0;
    max-width: 100%; /* Ensure content fits within the width of the modal */
    }

    .modal-fullscreen .modal-body {
        overflow-y: auto; /* Allow vertical scrolling */
        overflow-x: auto; /* Prevent horizontal scrolling */
        padding-left: 250px;
        margin-bottom: 10px;
        max-height: calc(100vh - 60px); /* Adjust the height as needed */
    }
        


    /* Apply basic formatting */
    .modal-content p {
        margin: 0;
        line-height: 1;
        font-size: 14px; /* Adjust font size as needed */
    }

    .modal-content table {
        width: 100%;
        border-collapse: collapse;
    }

    .modal-content table td {
        border: 1px solid #ddd; /* Add borders if necessary */
        padding: 8px;
    }


    table {
        width: 100%;
        border-collapse: collapse; /* Ensures there are no gaps between borders */
    }

    th, td {
        border: 1px solid black; /* Adds border to table cells */
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2; /* Optional: Adds background color to table headers */
    }

    /* Optional: Zebra striping for better readability */
    tr:nth-child(even) {
        background-color: #f9f9f9;
    }
</style>
<body>

    <h1>Uploaded Files</h1>

    <table>
        <tr>
            <th>#</th>
            <th>File</th>
            <th>File Type</th>
            <th>File Size</th>
        </tr>
        @if($files->isEmpty())
            <p>No files uploaded.</p>
        @else
            @foreach($files as $file)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if($file->htmlContent)
                            <!-- Display a document icon -->
                            <div class="document-icon" data-bs-toggle="modal" data-bs-target="#htmlContentModal" data-html-content="{{ htmlspecialchars($file->htmlContent, ENT_QUOTES, 'UTF-8') }}">
                                <i class="fas fa-file-word" style="font-size: 70px; cursor: pointer;"></i>
                            </div>
                        @elseif(in_array($file->type, ['image/jpeg', 'image/png']))
                            <img src="{{ Storage::url($file->filepath) }}" alt="Uploaded Image" 
                                style="max-width: 100px; max-height: 100px;"
                                data-bs-toggle="modal" 
                                data-bs-target="#imageModal" 
                                data-bs-image="{{ Storage::url($file->filepath) }}">
                        @elseif ($file->type == 'application/pdf')
                            <a href="{{ $file->pdfUrl }}" target="_blank">
                                <i class="fas fa-file-pdf" style="font-size: 70px; cursor: pointer;"></i></a>
                        @else
                            <p>Not an image file.</p>
                        @endif
                    </td>
                    <td>{{ $file->filename }}</td>
                    <td>{{ $file->size }} bytes</td>
                </tr>
            @endforeach
        @endif
    </table>



    <!-- Modal for Image Preview -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="modalImage" src="" alt="Full-size Image" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <!-- Full-screen modal -->
    <div class="modal fade" id="htmlContentModal" tabindex="-1" aria-labelledby="htmlContentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="htmlContentModalLabel">Document Content</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- This is where the document content will be displayed -->
                    <div id="modalContent" class="small-word-content"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        
        function cleanHTMLContent(html) {
            // Create a temporary DOM element to parse the HTML
            var tempDiv = document.createElement('div');
            tempDiv.innerHTML = html;

            // Remove unwanted elements or attributes
            var styles = tempDiv.querySelectorAll('style, script, head');
            styles.forEach(el => el.remove());

            return tempDiv.innerHTML;
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Check if the elements exist before adding event listeners
            var imageModal = document.getElementById('imageModal');
            if (imageModal) {
                var modalImage = document.getElementById('modalImage');
                imageModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget; // Button that triggered the modal
                    var imageUrl = button.getAttribute('data-bs-image'); // Extract info from data-bs-* attributes
                    modalImage.src = imageUrl;
                });
        }


        
        var htmlContentModal = document.getElementById('htmlContentModal');
        if (htmlContentModal) {
            htmlContentModal.addEventListener('show.bs.modal', function (event) {
                var element = event.relatedTarget;
                var htmlContent = element.getAttribute('data-html-content');
                var modalContent = document.getElementById('modalContent');
                if (htmlContent) {
                    modalContent.innerHTML = decodeHTML(htmlContent);
                } else {
                    modalContent.innerHTML = '<p>No content available</p>';
                }
            });
        }

        function decodeHTML(html) {
            var txt = document.createElement('textarea');
            txt.innerHTML = html;
            return txt.value;
        }
            });

        var documentIcons = document.querySelectorAll('.document-icon');

        documentIcons.forEach(function(icon) {
            icon.addEventListener('click', function() {
                var htmlContent = icon.getAttribute('data-html-content');
                var modalContent = document.getElementById('modalContent');

                // Decode and set the HTML content in the modal
                modalContent.innerHTML = decodeHTML(htmlContent);
        });


        function decodeHTML(html) {
            var txt = document.createElement('textarea');
            txt.innerHTML = html;
            return txt.value;
        }
        window.onclick = function(event) {
            var modal = document.getElementById('modal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    });

    </script>
</body>
</html>
