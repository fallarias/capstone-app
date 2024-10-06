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
    <script>
    function filterTable() {
        const notFinishedCheckbox = document.getElementById('not-finished');
        const finishedCheckbox = document.getElementById('finished');
        const overdueCheckbox = document.getElementById('overdue');
        const rows = document.querySelectorAll('#data-table tbody tr');

        let showAnyRow = false; // Flag to determine if any checkbox is checked

        rows.forEach(row => {
            const finishedCell = row.cells[4].textContent.trim();
            const deadline = new Date(row.cells[5].textContent.trim());
            const today = new Date();

            let showRow = true; // Default to show the row

            // Determine whether to show the row based on checkboxes
            if (notFinishedCheckbox.checked) {
                if (finishedCell === "Not Finish") {
                    showRow = true;
                    showAnyRow = true; // At least one row will be shown
                } else {
                    showRow = false;
                }
            } 
            else if (finishedCheckbox.checked) {
                if (finishedCell !== "Not Finish") {
                    showRow = true;
                    showAnyRow = true; // At least one row will be shown
                } else {
                    showRow = false;
                }
            } 
            else if (overdueCheckbox.checked) {
                if (deadline < today) {
                    showRow = true;
                    showAnyRow = true; // At least one row will be shown
                } else {
                    showRow = false;
                }
            } 
            else {
                // If no checkbox is checked, show all rows
                showRow = true;
            }

            // Set row visibility
            row.style.display = showRow ? '' : 'none';
        });
        
        // If no checkbox is checked, reset all rows to visible
        if (!notFinishedCheckbox.checked && !finishedCheckbox.checked && !overdueCheckbox.checked) {
            rows.forEach(row => {
                row.style.display = '';
            });
        }
    }
</script>

    <style>
        th, td { 
            text-align: center; 
            border: 1px solid black; 
        }
        button { 
            font-size: 17px; 
        }
        /* New Styles */
        .table-container {
            max-width: 100%; /* Prevent overflow */
            overflow-x: auto; /* Enable horizontal scroll if necessary */
        }
        #data-table {
            width: 100%; /* Table takes full width of its container */
            table-layout: fixed; /* Fixed table layout */
        }
    </style>
</head>
<body>
    @include('components.app-bar') 
    <div style="display: flex; flex-direction: column; align-items: center; margin-top: 40px; width: 100%; padding: 0 20px;">
        
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

        <!-- Filter Checkboxes -->
        <div style="display: flex; justify-content: center; margin-bottom: 20px;">
            <label style="margin-right: 1px;"><input type="checkbox" id="not-finished" onchange="filterTable()"> Not Finished</label>
            <label style="margin-right: 5px;"><input type="checkbox" id="finished" onchange="filterTable()"> Finished</label>
            <label><input type="checkbox" id="overdue" onchange="filterTable()"> Overdue</label>
        </div>

        <!-- Print and Download Buttons -->
        <div style="margin-bottom: 20px;">
            <button onclick="printTable()" class="btn btn-primary">Print / Download Table</button>
        </div>

        <!-- Table Container -->
        <div class="table-container">
            <table id="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User ID</th>
                        <th>Task ID</th>
                        <th>Start</th>
                        <th>Finished</th>
                        <th>Deadline</th>
                        <th>Office Name</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaction as $task)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $task->user_id }}</td>
                            <td>{{ $task->task_id }}</td>
                            <td>{{$task->start}}</td>
                            <td>
                                @if (is_null($task->finished))
                                    <p>Not Finish</p>
                                @else
                                    {{ $task->finished }}
                                @endif
                            </td>
                            <td>{{ $task->deadline }}</td>
                            <td>{{ $task->office_name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">No tasks found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <a href="{{ url('/dashboard') }}">Back</a>
</body>
</html>
