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
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">   
    <title>Documents</title>



<script>
document.addEventListener('mousedown', function(event) {
    if (!event.target.closest('input[type="checkbox"]')) {
        event.preventDefault(); // Prevents the checkbox from changing state
    }
}, true);


    function filterTable() {
        const notFinishedCheckbox = document.getElementById('not-finished');
        const finishedCheckbox = document.getElementById('finished');
        const overdueCheckbox = document.getElementById('overdue');

        const checkboxes = [notFinishedCheckbox, finishedCheckbox, overdueCheckbox];

        checkboxes.forEach(checkbox => {
            checkbox.onchange = function(event) {
                // Uncheck all other checkboxes
                checkboxes.forEach(otherCheckbox => {
                    if (otherCheckbox !== this) {
                        otherCheckbox.checked = false;
                    }
                });
                // Call filter logic
                applyFilter();
                event.stopPropagation(); // Prevents interference
            };
        });

        applyFilter(); // Initial call
    }

    document.addEventListener("DOMContentLoaded", function() {
        filterTable();
    });
</script>


    <script>
function fetchAuditTrails() {
    $.ajax({
        url: '{{ route('admin.auditTrails') }}', // Update with your actual route name
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            const tableBody = $('#logTable tbody');
            tableBody.empty(); // Clear the existing table data

            response.transactions.forEach((task, index) => {
                const startDate = formatDateTime(task.start);
                const deadlineDate = formatDateTime(task.deadline);
                const now = new Date();
                const deadline = new Date(task.deadline);

                let status = '';
                if (!task.finished && deadline < now) {
                    status = 'Overdue'; // Task is overdue
                } else if (!task.finished) {
                    status = 'Pending'; // Task is not finished
                } else {
                    status = formatDateTime(task.finished); // Task is finished
                }

                tableBody.append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>${task.user_id}</td>
                        <td>${task.task_id}</td>
                        <td>${startDate}</td>
                        <td>${status}</td> <!-- Display the correct status -->
                        <td>${deadlineDate}</td>
                        <td>${task.office_name}</td>
                    </tr>
                `);
            });
            applyFilter();
        },
        error: function(xhr) {
            console.error('Error fetching audit trails:', xhr);
        }
    });
}

// Helper function to format date and time
function formatDateTime(dateString) {
    const options = {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
        hour12: true,
    };
    const formattedDate = new Intl.DateTimeFormat('en-US', options).format(new Date(dateString));
    return formattedDate.replace(/^\w+/, (month) => month + '.');
}


    // Set an interval to auto-reload the table every 30 seconds
    setInterval(fetchAuditTrails, 7000);

    // Fetch audit trails when the page loads
    $(document).ready(fetchAuditTrails);

    function printTable() {
        const visibleRows = getVisibleRows();
        const printWindow = window.open('', '', 'height=600,width=800');
        
        printWindow.document.write('<html><head><title>Print Table</title>');
        printWindow.document.write('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">');
        printWindow.document.write('</head><body>');
        
        // Add table structure with the rows
        printWindow.document.write('<table class="table table-bordered">');
        printWindow.document.write('<thead><tr>');
        printWindow.document.write('<th>#</th><th>User ID</th><th>Task ID</th><th>Start</th><th>Status</th><th>Deadline</th><th>Office Name</th>');
        printWindow.document.write('</tr></thead>');
        printWindow.document.write('<tbody>');
        printWindow.document.write(visibleRows);
        printWindow.document.write('</tbody></table>');
        
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        
        printWindow.print();
    }

    function getVisibleRows() {
        const rows = document.querySelectorAll('#logTable tbody tr');
        let visibleRows = '';
        
        rows.forEach(row => {
            if (row.style.display !== 'none') {
                visibleRows += row.outerHTML; // Capture the entire HTML content of each visible row
            }
        });

        return visibleRows;
    }

        


    function applyFilter() {
        const notFinishedCheckbox = document.getElementById('not-finished');
        const finishedCheckbox = document.getElementById('finished');
        const overdueCheckbox = document.getElementById('overdue');

        const rows = document.querySelectorAll('#logTable tbody tr');
        const today = new Date();

        rows.forEach(row => {
            const finishedCell = row.cells[4].textContent.trim();
            const deadline = new Date(row.cells[5].textContent.trim());
            console.log("Raw deadline:", row.cells[5].textContent.trim());
            console.log("Parsed deadline:", deadline);
            console.log("Real deadline:", today);
            let showRow = true;

            // Apply filter logic based on which checkbox is checked
            if (notFinishedCheckbox.checked) {
                showRow = finishedCell === "Pending";
            } else if (finishedCheckbox.checked) {
                showRow = finishedCell !== "Pending" && finishedCell !== "" && finishedCell !== "Overdue";
            } else if (overdueCheckbox.checked) {
                const finishedDate = new Date(finishedCell);
                showRow = finishedCell === "Overdue" || (finishedCell !== "" && finishedDate > deadline);
            } else {
                // If no checkbox is checked, show all rows
                showRow = true;
            }

            // Set row visibility based on filtering
            row.style.display = showRow ? '' : 'none';
        });
    }

    // Attach the filter function to the checkboxes when the page is ready
    document.getElementById('not-finished').addEventListener('change', filterTable);
    document.getElementById('finished').addEventListener('change', filterTable);
    document.getElementById('overdue').addEventListener('change', filterTable);

    // Call the function initially to apply any filters that might be set
    window.onload = filterTable;

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
        background-color: #ffffff; /* White background for the table */
        border: 1px solid #ffffff; /* White border for the entire table */
    }
    

    #logTable th, #logTable td {
        
        padding: 12px 15px;
        border: 1px solid #222; /* White border for table cells */
        text-align: center;
    }

    #logTable thead {
        background-color: #222;
        color: #fff;
        text-align: center;
    }

    #logTable tbody tr {
        background-color: white;
    }

    #logTable tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    #logTable tbody tr:hover {
        background-color: #3a3a3a;
        color: #00ff99;
        cursor: pointer;
    }
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    animation: fadeIn 0.4s;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}




    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
        }
    }

    /* Change the color of the checkbox */
input[type="checkbox"]:checked {
    accent-color: green; /* Sets the checkbox color to green */
}

    </style>
</head>
<body>
    @include('components.app-bar') 
    <div style="display: flex; justify-content: center; margin-top: 40px; margin-bottom: 20px; margin-left: 200px;">
        
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
        <div class="checkbox-container">
            <label class="check-pending">
                <input type="checkbox" id="not-finished" onchange="filterTable()">
                <span class="text-pending">Pending</span>
            </label>
            <label class="check-finished">
                <input type="checkbox" id="finished" onchange="filterTable()">
                <span class="text-finished">Finished</span>
            </label>
            <label class="check-overdue">
                <input type="checkbox" id="overdue" onchange="filterTable()">
                <span class="text-overdue">Overdue</span>
            </label>
        </div>


        <!-- Print and Download Buttons -->
        <div class="printbtn1">
            <button onclick="printTable()" class="btn-pulse">Print / Download Table</button>
        </div>
        </div>

        

        <!-- Table Container -->
        <div class="logtable-container">
    <div class="logtable">
        <table id="logTable" class="listTable2">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>User ID</th>
                    <th>Task ID</th>
                    <th>Start</th>
                    <th>Status</th>
                    <th>Deadline</th>
                    <th>Office Name</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaction as $task)
                    <tr data-deadline="{{ $task->deadline }}" data-finished="{{ $task->finished }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $task->user_id }}</td>
                        <td>{{ $task->task_id }}</td>
                        <td>{{ $task->start }}</td>
                        <td class="status-cell">
                            @if (is_null($task->finished) && $task->deadline < now())
                                <p>Overdue</p>
                            @elseif (is_null($task->finished))
                                <p>Pending</p>
                            @else
                                <p>{{$task->finished}}</p>
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
