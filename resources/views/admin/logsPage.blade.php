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
    <title>Document</title>
</head>
<body>

<div style="text-align:center">
    @include('components.app-bar') 

    <!-- Filter Form -->
    <div style="display: flex; justify-content: center; margin-top: 20px; margin-bottom: 20px; margin-left: 200px;">
        <form id="searchForm" onsubmit="return searchTask()" style="display: flex; gap: 15px; align-items: center; margin-bottom: 20px;">
            <style>
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
    background-color:#ccc;
}

#logTable tr:nth-child(even) {
    background-color:#ccc;
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

            <!-- Search Task Name -->
            <input type="text" id="task_name" class="form-control" placeholder="Search" title="Search by task name">
            <!-- Filter Button -->
            <button type="submit" class="btn btn-primary">search</button>

                    <!-- Download Icon -->
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

    <div style="display: flex; justify-content: center; margin-top: -20px; width:1000px; margin-left:400px">
        <table id="logTable" border="1">
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

    <!-- Pagination -->
    <div id="pagination" style="display: flex; justify-content: center; align-items: center; margin-top: 20px;
    margin-left: 200px;">
        <button onclick="prevPage()" class="elastic-btn">
            <i class="fas fa-caret-left"></i>
        </button>
        <span id="pageNumbers" style="margin: 0 20px;"></span>
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

</body>
</html>
