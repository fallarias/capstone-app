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
    <script>
        function confirmAction(event, actionType, formId) {
            event.preventDefault(); // Prevent the form from submitting immediately

            Swal.fire({
                title: `Are you sure you want to ${actionType} this user?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit(); // Submit the form if confirmed
                }
            });
        }
    </script>
    <style>
                    #logTable {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    font-size: 1em;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
}

#logTable thead {
    background-color: #222;
    color: #fff;
    text-align: center;
}

#logTable th, #logTable td {
    padding: 12px 15px;
    border: 1px solid #444;
    text-align: center;
}

#logTable tr {
    background-color: white;
}

#logTable tr:nth-child(even) {
    background-color:white;
}

#logTable tr:hover {
    background-color: #3a3a3a;
    color: #00ff99;
    cursor: pointer;
}

#logTable th {
    background: linear-gradient(90deg, #18392B, #18392B);
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
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

            .form-control {
                    width: 250px;
                    padding: 5px;
                    border-radius: 5px;
                    border: 1px solid #ccc;
                    transition: all 0.3s ease-in-out;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
    </style>
</head>
<body>
<div style="text-align:center">
    @include('components.app-bar', ['admin' => $admin])

    <div style="display: flex; justify-content: center; margin-top: 20px; margin-bottom: 20px; margin-left: -50px;">
        <form id="searchForm" onsubmit="return searchTask()" style="display: flex; gap: 15px; align-items: center; margin-bottom: 20px;">

        <input type="text" id="task_name" class="form-control" placeholder="Search" title="Search by task name">
            <!-- Filter Button -->
            <button type="submit" class="btn btn-primary">search</button>

                    <!-- Download Icon -->
        <!-- Download Icon -->


        <!-- Delete Icon
        <button type="button" class="trash-btn" title="Delete">
            <i class="fas fa-trash-alt"></i>
        </button>
         -->
        </form>
    </div>
    <div style="display: flex; justify-content: center; margin-top: -10px; width:1000px; margin-left:400px">
        
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


        <table id="logTable" border="1">
    <thead>
        <tr>
            <th>No.</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Middle Name</th>
            <th>Email</th>
            <th>Account Type</th>
            <th>Status</th>
            <th>Profile Picture</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($user as $counter => $row)
            <tr style="border: none">
                <td style="font-weight: bold;">{{ $loop->iteration }}</td> 
                <td>{{ $row->firstname }}</td>
                <td>{{ $row->lastname }}</td>
                <td>{{ $row->middlename }}</td>
                <td style="color:#07bdff; text-decoration: underline">{{ $row->email }}</td>
                <td>{{ $row->account_type }}</td>
                <td>{{ $row->status }}</td>
                <td>{{ $row->profile_picture }}</td>
                <td style="display: flex; gap: 20px; justify-content: center; padding: 10px;">
                    @if ($row->status == "Not Accepted" || $row->status == "Not accepted")
                        <form id="acceptForm{{ $row->user_id }}" action="{{ route('admin.accept', $row->user_id) }}" method="POST">
                            @csrf
                            <button style="background-color:#28a745; color:wheat; border-radius: 3px; width:90px; border:none; padding:5px; cursor: pointer;" type="button" onclick="confirmAction(event, 'accept', 'acceptForm{{ $row->user_id }}')">Accept <br> User</button>
                        </form>
                    @else
                        <form id="rejectForm{{ $row->user_id }}" action="{{ route('admin.reject', $row->user_id) }}" method="POST">
                            @csrf
                            <button style="background-color:red; color:wheat; border-radius: 3px; width:90px; border:none; padding:5px ;cursor: pointer;" type="button" onclick="confirmAction(event, 'deactivate ', 'rejectForm{{ $row->user_id }}')">Deactivate User</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9">No Users Found</td>
            </tr>
        @endforelse
    </tbody>
</table><br>
    </div>
    <div id="pagination" style="display: flex; justify-content: center; align-items: center; margin-top: 20px;
    margin-left: 300px;">
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
    const rowsPerPage = 10;
    let currentPage = 1;

    function searchTask() {
    const searchValue = document.getElementById('task_name').value.toLowerCase().trim();
    const rows = document.querySelectorAll('#logTable tbody tr');

    // Split the search value into words
    const searchTerms = searchValue.split(' ').filter(term => term);

    let found = false;

    rows.forEach((row) => {
        const firstName = row.cells[1].innerText.toLowerCase();
        const lastName = row.cells[2].innerText.toLowerCase();
        const middlename = row.cells[3].innerText.toLowerCase();
        const email = row.cells[4].innerText.toLowerCase();
        const status = row.cells[5].innerText.toLowerCase();
        const account = row.cells[6].innerText.toLowerCase();
        const department = row.cells[7].innerText.toLowerCase();
        // Check if all search terms match either the first name or last name
        const matches = searchTerms.every(term => 
            firstName.includes(term) || lastName.includes(term) || middlename.includes(term) || email.includes(term) || status.includes(term) || account.includes(term) || department.includes(term)
        );

        if (matches) {
            row.style.display = ''; // Show the row
            found = true;
        } else {
            row.style.display = 'none'; // Hide the row
        }
    });

    if (!found) {
        Swal.fire({
            title: 'No Match',
            text: `No users found matching "${searchValue}"`,
            icon: 'warning',
            confirmButtonText: 'OK'
        });
    }

    return false; // Prevent form submission
}

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
        const totalRows = document.querySelectorAll('#logTable tbody tr').length;
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
