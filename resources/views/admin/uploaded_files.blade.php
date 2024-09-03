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
    .small-word-content {
        font-size: 0.1px; /* Set an even smaller base font size */
        line-height: 0.3; /* Tighten the line height */
        max-width: 100px; /* Limit the width */
        padding: 3px; /* Add some padding */
        border: 1px solid #ccc; /* Add a border for visual separation */
        margin-top: 5px; /* Add space between content and previous element */
        white-space: pre-wrap; /* Preserve the original spacing and line breaks */
        cursor: pointer; /* Indicate that the content is clickable */
    }
    .small-word-content p,
    .small-word-content h1, 
    .small-word-content h2, 
    .small-word-content h3, 
    .small-word-content h4, 
    .small-word-content h5, 
    .small-word-content h6,
    .small-word-content li,
    .small-word-content span {
        font-size: 1px !important; /* Ensure all elements have the small font size */
        margin: 0 !important; /* Remove any default margins */
        padding: 0 !important; /* Remove any default padding */
        line-height: 1.0 !important; /* Ensure consistent line height */
    }
    .small-word-content p,
    .small-word-content li {
        margin-bottom: 1px !important; /* Minimize space between paragraphs and list items */
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

</style>
<body>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <h1>Uploaded Files</h1>

    @if($files->isEmpty())
    <p>No files uploaded.</p>
    @else
    <ul>
        @foreach($files as $file)
            <li>
                <p>({{ $file->size }} bytes, {{ $file->filename }})</p>
                @if($file->htmlContent)
                    <!-- Display the Word document content -->
                    <div class="small-word-content" data-bs-toggle="modal" data-bs-target="#htmlContentModal" data-html-content="{{ htmlspecialchars($file->htmlContent, ENT_QUOTES, 'UTF-8') }}">
                        {!! $file->htmlContent !!}
                    </div>
                @elseif(in_array($file->type, ['image/jpeg', 'image/png']))
                    <img src="{{ Storage::url($file->filepath) }}" alt="Uploaded Image" 
                         style="max-width: 100px; max-height: 100px;"
                         data-bs-toggle="modal" 
                         data-bs-target="#imageModal" 
                         data-bs-image="{{ Storage::url($file->filepath) }}">
                @elseif(in_array($file->type, ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']))
                    <a href="https://docs.google.com/viewer?url={{ Storage::url($file->filepath) }}" target="_blank">
                        <i class="fas fa-file-word"></i> View Document
                    </a>
                @else
                    <p>Not an image file.</p>
                @endif
            </li>
        @endforeach
    </ul>
    @endif

    <!-- Full-Screen Modal for HTML Content -->
    <div class="modal fade" id="htmlContentModal" tabindex="-1" aria-labelledby="htmlContentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-fullscreen modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="htmlContentModalLabel">HTML Content</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modalHtmlContent" class="small-word-content"></div>
            </div>
        </div>
    </div>
</div>

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

    <script>
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
        var modalHtmlContent = document.getElementById('modalHtmlContent');
        htmlContentModal.addEventListener('show.bs.modal', function (event) {
            var element = event.relatedTarget; // Element that triggered the modal
            var htmlContent = element.getAttribute('data-html-content'); // Extract the HTML content from data attribute
            if (htmlContent) {
                modalHtmlContent.innerHTML = decodeHTML(htmlContent); // Decode and set HTML content
            } else {
                modalHtmlContent.innerHTML = '<p>No content available</p>';
            }
        });
    }

    function decodeHTML(html) {
        var txt = document.createElement('textarea');
        txt.innerHTML = html;
        return txt.value;
    }
});

    </script>
</body>
</html>
