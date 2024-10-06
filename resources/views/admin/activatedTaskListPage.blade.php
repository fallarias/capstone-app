<!DOCTYPE html>
<html lang="en">
<head>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">   
    <title>Document</title>
</head>
<body>
    @include('components.app-bar') 
    <div style="display: flex; justify-content: center; margin-top: 20px; margin-bottom: 20px; margin-left: -50px;">
        <form action="{{ route('admin.listOfTaskPage') }}" method="GET" style="display: flex; gap: 15px; align-items: center; margin-bottom: 20px;">
            <!-- Custom CSS Styles -->
            <style>
                .form-control {
                    width: 250px;
                    padding: 5px;
                    border-radius: 5px;
                    border: 1px solid #ccc;
                    transition: all 0.3s ease-in-out;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    margin-left: 260px;
                }

                .form-control:focus {
                    outline: none;
                    border-color: #007bff;
                    box-shadow: 0 6px 12px rgba(0, 123, 255, 0.2);
                }

                select.form-control {
                    width: 200px;
                    height: 40px;
                }

                button.btn-primary {
                    background-color: #007bff;
                    margin-top: -20px;
                    color: white;
                    border: none;
                    border-radius: 5px;
                    padding: 10px 20px;
                    cursor: pointer;
                    transition: background-color 0.3s ease, transform 0.2s ease;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                }

                button.btn-primary:hover {
                    background-color: #0056b3;
                    transform: scale(1.05);
                }

                input.form-control:hover, select.form-control:hover {
                    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
                }

                input.form-control {
                    width: 580px; /* Adjusting width for a larger search bar */
                }

                .dl-btn, .trash-btn {
                    background-color: transparent;
                    border: none;
                    cursor: pointer;
                    margin-top: -20px;
                }

                .dl-btn i {
                    font-size: 30px;
                    color: gray;
                }

                .trash-btn i {
                    font-size: 30px;
                    color: red;
                }

                .elastic-btn {
                    width: 50px;
                    height: 50px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    background-color: #18392B;
                    border: none;
                    border-radius: 50%;
                    cursor: pointer;
                    transition: all 0.3s ease-in-out;
                }

                .elastic-btn i {
                    font-size: 24px;
                    color: white;
                }

                .elastic-btn:hover {
                    transform: scale(1.1);
                }

                .elastic-btn:active {
                    animation: elastic 0.2s ease;
                }

                @keyframes elastic {
                    0% {
                        transform: scale(1.1);
                    }
                    50% {
                        transform: scale(1.2);
                    }
                    100% {
                        transform: scale(1);
                    }
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                    border-radius: 8px;
                    overflow: hidden;
                }

                th, td {
                    padding: 12px 20px;
                    text-align: center;
                }

                thead th {
                    background-color: #007bff;
                    color: white;
                    font-size: 16px;
                    text-transform: uppercase;
                }

                tbody tr {
                    background-color: #f9f9f9;
                    border-bottom: 1px solid #ddd;
                }

                tbody tr:nth-child(even) {
                    background-color: #fff;
                }

                tbody tr:hover {
                    background-color: #f1f1f1;
                }
            </style>

            <!-- Search Task Name -->
            <input type="text" name="task_name" class="form-control" placeholder="Search task name" value="{{ request('task_name') }}" title="Search by task name">
            <!-- Search Button -->
            <button type="submit" class="btn btn-primary">Search</button>
            <!-- Download Icon -->
            <button type="button" class="dl-btn" title="Download" id="downloadPdf">
                <i class="fas fa-download"></i>
            </button>
            <!-- Delete Icon -->
            <button type="button" class="trash-btn" title="Delete">
                <i class="fas fa-trash-alt"></i>
            </button>
        </form>
    </div>

    <div style="display: flex; justify-content: center; margin-top: 20px; width: 1000px; margin-left: 400px;">
        @if(session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Great...',
                    text: @json(session('success')),
                    confirmButtonText: 'OK'
                });
            </script>
        @endif

        <table border="1px" id="logTable">
            <thead>
                <th>No.</th>
                <th>Task</th>
                <th>Date Created</th>
                <th>Template</th>
                <th>Template Name</th>
            </thead>
            <tbody>
                @forelse($data as $task)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $task->name }}</td>
                        <td>{{ $task->date }}</td>
                        <td style="display: flex; justify-content: center; align-items: center; text-align: center;">
                            @forelse($task->files as $file)
                                @if($file->pdfUrl)
                                    <a href="{{ $file->pdfUrl }}" target="_blank">
                                        <i class="fas fa-file-pdf" style="font-size: 70px; cursor: pointer;"></i>
                                    </a>
                                @else
                                    <p>No PDF available.</p>
                                @endif
                            @empty
                                <p>No files for this task.</p>
                            @endforelse
                        </td>
                        <td>{{$file->filename}}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No tasks found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div id="pagination" style="display: flex; justify-content: center; align-items: center; margin-top: 20px; margin-left: 200px;">
        <button onclick="prevPage()" class="elastic-btn">
            <i class="fas fa-caret-left"></i>
        </button>
        <span id="pageNumbers" style="margin: 0 20px;"></span>
        <button onclick="nextPage()" class="elastic-btn">
            <i class="fas fa-caret-right"></i>
        </button>
    </div>

    <script>
        const rowsPerPage = 5;
        let currentPage = 1;

        function paginateTable() {
            const rows = document.querySelectorAll('#logTable tbody tr');
            const totalRows = rows.length;
            const totalPages = Math.ceil(totalRows / rowsPerPage);

            rows.forEach((row, index) => {
                row.style.display = (index >= (currentPage - 1) * rowsPerPage && index < currentPage * rowsPerPage) ? '' : 'none';
            });

            updatePagination(totalPages);
        }

        function updatePagination(totalPages) {
            const pageNumbers = document.getElementById('pageNumbers');
            pageNumbers.textContent = `Page ${currentPage} of ${totalPages}`;
        }

        function prevPage() {
            if (currentPage > 1) {
                currentPage--;
                paginateTable();
            }
        }

        function nextPage() {
            const rows = document.querySelectorAll('#logTable tbody tr');
            const totalPages = Math.ceil(rows.length / rowsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                paginateTable();
            }
        }

        // Initialize table pagination
        paginateTable();
    </script>
</body>
<script>
document.getElementById('downloadPdf').addEventListener('click', function() {
    // Remove pagination by displaying all rows
    const rows = document.querySelectorAll('#logTable tbody tr');
    rows.forEach(row => {
        row.style.display = '';  // Show all rows
    });

    // Get the table element
    const table = document.querySelector('table');

    // Use html2canvas to convert the table to canvas
    html2canvas(table).then(function(canvas) {
        const imgData = canvas.toDataURL('image/png');
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF();

        // Add the image of the table to the PDF
        pdf.addImage(imgData, 'PNG', 10, 10, 190, 0);  // Adjust positioning and size as needed

        // Save the PDF
        pdf.save('table.pdf');

        // Automatically open the print dialog
        window.open(pdf.output('bloburl'), '_blank');

        // Reapply pagination after PDF generation
        paginateTable();
    });
});

</script>
</html>
