<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <title>Document</title>
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

            /* Styling for download and delete icons */
            /* Styling for download and delete icons */
        .dl-btn {
            font-size: 10px; /* Adjust this value to change the size */
            background-color: transparent;
            border: none;
            cursor: pointer;
            margin-top: -20px;
            color: gray;
        }

        .trash-btn {
            font-size: 30px; /* Adjust this value to change the size */
            background-color: transparent;
            border: none;
            cursor: pointer;
            margin-top: -20px;
            color: red;
        }

        .icon-btn:hover {
            color: #0056b3;
        }
        .dl-btn i, .trash-btn i {
            font-size: 30px; /* Adjust this to your preferred size */
        }

        .elastic-btn {
                width: 50px;          /* Width of the button */
                height: 50px;         /* Height of the button */
                display: flex;        /* Align icon within button */
                justify-content: center;
                align-items: center;
                background-color: #18392B;
                border: none;
                border-radius: 50%;   /* Makes the button circular */
                cursor: pointer;
                transition: all 0.3s ease-in-out;
            }

            .elastic-btn i {
                font-size: 24px;      /* Resize the icon */
                color: white;         /* Icon color */
            }

            .elastic-btn i:hover {
                color: yellow;
                transform: scale(1.1); /* Elastic hover effect */
                background-color:darkgreen;
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


        </style>
</head>
<body>

<div style="text-align:center">
    @include('components.app-bar', ['admin' => $admin]) 

    <!-- Filter Form -->
    <div style="display: flex; justify-content: center; margin-top: 20px; margin-bottom: 20px; margin-left: -50px;">
    <form onsubmit="event.preventDefault(); searchTask();" style="display: flex; gap: 15px; align-items: center; margin-bottom: 20px;">
        <!-- Search Task Name -->
        <input type="text" id="task_name" name="task_name" class="form-control" placeholder="Search task name" title="Search by task name">

        <!-- Search Button -->
        <button type="button" class="btn btn-primary" onclick="searchTask()">Search</button>

        <!-- Delete Icon 
        <button type="button" class="trash-btn" title="Delete">
            <i class="fas fa-trash-alt"></i>
        </button>-->
    </form>
</div>

    <div style="display: flex; justify-content: center; margin-top: -10px; width:1000px; margin-left:400px">
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                border-radius: 8px;
                overflow: hidden;
            }
            th, td { text-align: center; background-color:white; }
            button { font-size: 17px; }
        </style>

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

        <table id="taskTable" border="1px">
            <thead>
                <th>No.</th>
                <th>Task</th>
                <th>Actions</th>
                <th>Template</th>
                <th>Template Name</th>
                <th>Status</th>
            </thead>
            <tbody>
                @forelse($data as $task)
                    <tr>
                        <td style="font-weight: bold;">{{ $loop->iteration }}</td>
                        <td>{{ $task->name }}</td>
                        <td style="display: flex; gap: 10px; justify-content: center; padding: 10px;">
                            <form action="{{ route('admin.editTaskPage', $task->task_id) }}" method="GET">
                                @csrf
                                <button type="submit" style="background-color: #4CAF50; color: white; border: none; padding: 13px 20px; border-radius: 5px; cursor: pointer; transition: background-color 0.3s ease;width:120px">Edit</button>
                            </form>
                            <form action="{{ route('admin.taskActivate', $task->task_id) }}" method="POST">
                                @csrf
                                <button type="submit" style="background-color: #0275d8; color: white; border: none; padding: 13px 20px; border-radius: 5px; cursor: pointer; transition: background-color 0.3s ease;width:120px">Activate</button>
                            </form>
                            <form action="{{ route('admin.deleteTask', $task->task_id) }}" method="POST">
                                @csrf
                                <button type="submit" style="background-color: #d9534f; color: white; border: none; padding: 13px 20px; border-radius: 5px; cursor: pointer; transition: background-color 0.3s ease;">Deactivate</button>
                            </form>
                        </td>

                        <td style="padding: 10px;">
                            @forelse($task->files as $file)
                                @if($file->thumbnailUrl)
                                    <a href="{{ $file->pdfUrl }}" target="_blank" style="text-decoration: none;">
                                        <img src="{{ $file->thumbnailUrl }}" alt="PDF Thumbnail" style="width: 100px; height: auto; border: 1px solid #ccc; border-radius: 5px; padding: 5px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); cursor: pointer; transition: transform 0.3s ease;">
                                    </a>
                                @else
                                    <a href="{{ $file->pdfUrl }}" target="_blank" style="text-decoration: none;">
                                        <i class="fas fa-file-pdf" style="font-size: 35px; color: #d9534f; cursor: pointer; margin:auto;padding: 5px; transition: transform 0.3s ease; border-radius: 5px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);"></i>
                                    </a>
                                @endif  
                            @empty
                                <p style="color: #777; font-style: italic;">No files for this task.</p>
                            @endforelse
                        </td>

                        <td>
                            {{$file->filename}}
                        </td>
                        <td>
                            @if($task->status == 0)
                                <p style="color: red;font-weight:bold">Deactivate</p>
                            @elseif ($task->status == 1)
                                <p style="color: blue;font-weight:bold">Activated</p>
                            @else
                                <p style="color: gray;font-weight:bold">None</p>
                            @endif
                        </td>
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
</div>
</body>

<script>
    const rowsPerPage = 5;
    let currentPage = 1;


    function searchTask() {
    const searchValue = document.getElementById('task_name').value.toLowerCase().trim();
    const rows = document.querySelectorAll('#taskTable tbody tr');

    const searchTerms = searchValue.split(' ').filter(term => term);
    let found = false;

    rows.forEach((task) => {
        const name = task.cells[1].innerText.toLowerCase();
        const matches = searchTerms.every(term => name.includes(term));

        if (matches) {
            task.style.display = ''; // Show the row
            found = true;
        } else {
            task.style.display = 'none'; // Hide the row
        }
    });

    // Show SweetAlert if no tasks were found
    if (!found && searchValue) {
        Swal.fire({
            icon: 'warning',
            title: 'No Tasks Found',
            text: `No tasks named "${searchValue}" match your search criteria.`,
            confirmButtonText: 'OK'
        });
    }
}


    function paginateTable() {
        const rows = document.querySelectorAll('#taskTable tbody tr');
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
        const totalRows = document.querySelectorAll('#taskTable tbody tr').length;
        const totalPages = Math.ceil(totalRows / rowsPerPage);

        if (currentPage < totalPages) {
            currentPage++;
            paginateTable();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        paginateTable();
    });
</script>
<script>
    // Function to refresh the stats every 5 seconds
    function refreshStats() {
        $.ajax({
            url: '/audit', // The route where you fetch updated stats
            method: 'GET',
            success: function(response) {
                // The data is fetched but not used for updating the page.
                console.log(response); // Optional: Log the data to the console for debugging
            }
        });
    }

    // Refresh the stats every 5 seconds (5000 milliseconds)
    setInterval(refreshStats, 30000);
</script>
</html>
