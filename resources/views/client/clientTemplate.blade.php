<!DOCTYPE html>
<html lang="en">
<head>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4"></script>
    <style>
        /* Custom App Bar Styles */
        .app-bar {
            background-color: #18392B;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 96px;
            padding-right: 20px;
            padding-left: 80px;
        }

        .app-bar .title {
            font-size: 74px;
            font-weight: bold;
            margin: 0;
        }

        .app-bar .nav-links {
            display: flex;
            gap: 50px;
        }

        .app-bar .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            margin-bottom: -5px;
        }

        .app-bar .nav-links a:hover {
            text-decoration: underline;
        }

        /* Optional: Styles for Main Content */
        .container {
            margin-top: 20px;
            padding: 20px;
        }

        .img-person{
        width: 60px; 
        height: 60px; 
        border-radius: 50%; 
        margin-bottom: -10px; 
        border: 4px solid rgb(3, 170, 67);
      }

      .custom-search-button {
    background-color: #4CAF50; /* Change to your desired color */
    color: white; /* Text color */
    font-size: 16px; /* Adjust font size */
    padding: 10px 20px; /* Adjust padding for button size */
    border: none; /* Remove border */
    border-radius: 4px; /* Optional: rounded corners */
    cursor: pointer; /* Change cursor on hover */
}

.custom-search-button:hover {
    background-color: #45a049; /* Darken color on hover */
}

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

    /* App bar styles */
    .app-bar .nav-links a {
        color: white;
        text-decoration: none;
        font-size: 16px;
        margin-bottom: -5px;
        transition: color 0.3s ease;
    }

    .app-bar .nav-links a:hover {
        color: #00b894;
        text-decoration: none;
    }

    /* Custom search button with hover animation */
    .custom-search-button {
        background-color: #18392B;
        color: white;
        font-size: 16px;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .custom-search-button:hover {
        background-color: #00b894;
        transform: scale(1.05);
    }

    /* Pagination buttons with hover animation */
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
        transition: color 0.3s ease;
    }

    .elastic-btn:hover {
        background-color: #00b894;
        transform: scale(1.1);
    }

    .elastic-btn:active {
        animation: elastic 0.2s ease;
    }

    @keyframes elastic {
        0% { transform: scale(1.1); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }


        </style>
</head>
<body>
<div class="app-bar">
<div class="search-container">
    <form onsubmit="event.preventDefault(); searchTask();" class="form-inline" action="/search" method="GET">
        <input type="text" id="task_name" name="task_name" class="form-control mr-sm-2" placeholder="Search task name" aria-label="Search by task name" style="margin-left:1100px">
        <button class="btn custom-search-button" type="button" onclick="searchTask()">
            <i class="fas fa-search" style="font-size: 20px;"></i> Search
        </button>
    </form>
    </div>
</div>

<div style="text-align:center">
    @include('components.clientDrawer') 


<div style="display: flex; justify-content: center; margin-top: 40px; width:1000px; margin-left:400px">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            text-align: center;
            background-color: white;
        }
        button {
            font-size: 17px;
        }

        /* Add hover effect for table rows */
        tr:hover {
            background-color: green; /* Your desired hover color */
            color: #00b894; /* Optional: Change text color on hover */
        }

        .btn-download {
            background-color: #00b894; /* Blue background */
            color: black; /* White text */
            border: none; /* No border */
            border-radius: 5px; /* Rounded corners */
            padding: 10px 15px; /* Padding for size */
            cursor: pointer; /* Pointer on hover */
            font-size: 16px; /* Font size */
            transition: background-color 0.3s ease, transform 0.2s ease; /* Transition for hover effects */
        }

        .btn-download:hover {
            background-color: #0056b3; /* Darker blue on hover */
            transform: scale(1.05); /* Slightly enlarge on hover */
        }

    </style>

    <table id="taskTable" border="1px">
        <thead>
            <th>No.</th>
            <th>Task</th>
            <th>Template</th>
            <th>Template Name</th>
            <th>Action</th>
        </thead>
        <tbody>
            @forelse($files as $task)
                <tr>
                    <td style="font-weight: bold;">{{ $loop->iteration }}</td>
                    <td>{{ $task->name }}</td>
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
                        <form action="{{route('client.clientTransaction', $file->task_id)}}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-download">
                                Download
                            </button>
                        </form>
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
</html>
