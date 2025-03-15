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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">   
    <title>Document</title>
</head>
<body>

<div style="text-align:center">
    @include('components.app-bar', ['admin' => $admin]) 

    <!-- Filter Form -->
    <div style="display: flex; justify-content: center; margin-top: 20px; margin-bottom: 20px; margin-left: 200px;">
        <form id="searchForm" onsubmit="return searchTask()" style="display: flex; gap: 15px; align-items: center; margin-bottom: 20px;">


            <!-- Search Task Name -->
            <input type="text" id="task_name" class="form-control1" placeholder="Search" title="Search by task name">
            <!-- Filter Button -->
            <button type="button" class="btn btn-primary1" onclick="searchTask()">Search</button>


                    <!-- Download Icon -->
        <!-- Download Icon -->
        <button type="button" class="dl-btn" title="Download" id="downloadPdf">
            <i class="fas fa-download"></i>
        </button>

        </form>
        </div>

        <!-- Delete Icon 
        <button type="button" class="trash-btn" title="Delete">
            <i class="fas fa-trash-alt"></i>
        </button>-->
        </form>
    </div>

    <div style="display: flex; justify-content: center; margin-top: -20px; width:1000px; margin-left:400px">
    <div class="table-responsive">
        <table class="listTable" id="logTable" border="1">
            <thead>
                <th>No.</th>
                <th>User ID</th>
                <th>Action</th>
                <th>Account Type</th>
                <th>Date</th>
            </thead>
            <tbody id="tableBody">
                @forelse($logs as $log)
                    <tr>
                        <td style="font-weight: bold; text-align:center">{{ $loop->iteration }}</td>
                        <td>{{ $log->user_id }}</td>
                        <td>{{ $log->action }}</td>
                        <td>{{ $log->account_type }}</td>
                        <td>{{ $log->Date }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">No Logs Found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

    <!-- Pagination -->
    <div id="pagination" class="nextprev">
        <button onclick="prevPage()" class="elastic-btn">
            <i class="fas fa-caret-left"></i>
        </button>
        <span class="pages" id="pageNumbers" style="margin: 0 20px;"></span>
        <button onclick="nextPage()" class="elastic-btn">
                <i class="fas fa-caret-right"></i>
    </button>

    </div>
</div>

<!-- JavaScript for filtering and pagination -->
<script>
    const rowsPerPage = 10;
    let currentPage = 1;

    function searchTask() {
        const searchValue = document.getElementById('task_name').value.toLowerCase(); 
        const rows = document.querySelectorAll('#logTable tbody tr'); 

        let found = false;

        rows.forEach((row) => {
            const action = row.cells[2].innerText.toLowerCase();
            if (action.includes(searchValue)) {
                row.style.display = '';
                found = true;
            } else {
                row.style.display = 'none';
            }
        });

        if (!found) {
            Swal.fire({
                title: 'No Match',
                text: `No tasks found matching "${searchValue}"`,
                icon: 'warning',
                confirmButtonText: 'OK'
            });
        }

        return false;
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
</body>
</html>
