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

        .img-person {
            width: 60px; 
            height: 60px; 
            border-radius: 50%; 
            margin-bottom: -10px; 
            border: 4px solid rgb(3, 170, 67);
        }

        .custom-search-button {
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .custom-search-button:hover {
            background-color: #45a049;
        }

        /* Card Styles */
        .card {
            width: 250px;
            margin: 10px;
            border-radius: 8px;
            overflow: hidden;
            background-color: #222;
            color: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .card-body {
            padding: 15px;
        }

        .card-title {
            color: #222;
            font-size: 18px;
            font-weight: bold;
        }

        .progress-bar {
            height: 5px;
            background-color: #45a049;
            width: 100%;
            transition: width 0.5s ease;
        }

        .progress-container {
            background-color: #444;
            height: 5px;
            border-radius: 5px;
            overflow: hidden;
            margin-top: 10px;
        }

        .progress-text {
            position: absolute;
            right: 15px;
            top: 160px;
            font-size: 14px;
            color: #222;
        }

        /* Flexbox for Cards */
        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .filter-dropdown{
            width: 300px;
            margin-left: 820px;
            margin-top: 80px;
            margin-bottom: -130px;
        }

    </style>
</head>
<body>
    <div class="app-bar">

    </div>
    

    @include('components.clientDrawer')

    <div class="filter-dropdown">
        <select id="filterStatus" class="form-control">
            <option value="all">All</option>
            <option value="complete">Complete</option>
            <option value="ongoing">Ongoing</option>
            <option value="failed">Failed</option>
        </select>
    </div>


    <h2 style="margin-left: 300px;margin-top: 20px">Document Request</h2>


    <div style="display: flex; justify-content: center; gap: 30px; align-items: center; margin-top: 40px;">
    <!-- Start Date Filter -->
    <div style="display: flex; flex-direction: column; align-items: center; margin-left:-350px">
        <label for="startDate" style="font-weight: bold;">From:</label>
        <input type="date" id="startDate" class="form-control" style="width: 180px; margin-top:-40px ">
    </div>

    <!-- End Date Filter -->
    <div style="display: flex; flex-direction: column; align-items: center; margin-left:-180px">
        <label for="endDate" style="font-weight: bold;">To:</label>
        <input type="date" id="endDate" class="form-control" style="width: 180px; margin-top:-40px">
    </div>
</div>


    <div style="display: flex; justify-content: center; margin-top: -20px; width:1000px; margin-left:400px">
    <div class="container">
        @if ($tasks->isEmpty())
            <div class="alert alert-info text-center">
                No tasks history available.
            </div>
        @else
            <table class="table mt-4" style="background-color: rgba(128, 128, 128, 0.1); border: 2px solid black; border-radius: 8px; overflow: hidden; box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);">
            <thead style="background-color: rgba(128, 128, 128, 0.2); border-bottom: 2px solid black;">
                <tr>
                    <th style="border-right: 1px solid black;">Date</th>
                    <th style="border-right: 1px solid black;">Time</th>
                    <th style="border-right: 1px solid black;">Type</th>
                    <th>Status</th>
                    <th id="ratingsHeader" style="display: none;">Ratings</th> <!-- Hidden by default -->
                </tr>
            </thead>

            <tbody>
                @foreach ($tasks as $list)
                    <tr style="background-color: white; border-top: 1px solid black; cursor: pointer;"
                        onmouseover="this.style.backgroundColor='black'; this.style.color='limegreen';"
                        onmouseout="this.style.backgroundColor='rgba(128, 128, 128, 0.1)'; this.style.color='';">
                        <td>{{$loop->iteration}}</td>
                        <td>{{$list->transaction_id}}</td>
                        <td style="border-right: 1px solid black;">{{ $list->name }}</td>
                        <td>{{$list->status}}</td>
                        <td class="ratingsCell" style="display: none;">
                            <form action="{{ route('client.clientRatingPage', ['transaction_id' => $list->transaction_id]) }}" method="get" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm" style="background-color: #00b894; border-radius:4px;cursor: pointer; font-size: 16px; color: black;">Rate us</button>
                            </form>
                        </td> <!-- Hidden by default -->
                    </tr>
                @endforeach
            </tbody>

            </table>
        @endif
    </div>
</div>


<script>
    document.getElementById("filterStatus").addEventListener("change", function () {
        let filterValue = this.value.toLowerCase();
        let rows = document.querySelectorAll("tbody tr");
        let ratingsHeader = document.getElementById("ratingsHeader");
        let ratingsCells = document.querySelectorAll(".ratingsCell");

        if (filterValue === "all") {
            ratingsHeader.style.display = "";
            ratingsCells.forEach(cell => cell.style.display = "");
        } else {
            ratingsHeader.style.display = "none";
            ratingsCells.forEach(cell => cell.style.display = "none");
        }

        rows.forEach(row => {
            let statusCell = row.querySelector("td:last-child");
            let status = statusCell.textContent.trim().toLowerCase();

            if (filterValue === "all" || status === filterValue) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>




<script>
    document.addEventListener("DOMContentLoaded", function() {
        const statusFilter = document.getElementById("statusFilter");
        const startDateFilter = document.getElementById("startDate");
        const endDateFilter = document.getElementById("endDate");
        const rows = document.querySelectorAll(".task-row");

        function filterTable() {
            const selectedStatus = statusFilter.value;
            const startDate = startDateFilter.value ? new Date(startDateFilter.value) : null;
            const endDate = endDateFilter.value ? new Date(endDateFilter.value) : null;

            rows.forEach(row => {
                const rowStatus = row.getAttribute("data-status");
                const rowDate = new Date(row.getAttribute("data-date"));
                let showRow = true;

                // Filter by status
                if (selectedStatus !== "all" && rowStatus !== selectedStatus) {
                    showRow = false;
                }

                // Filter by date range
                if (startDate && rowDate < startDate) {
                    showRow = false;
                }
                if (endDate && rowDate > endDate) {
                    showRow = false;
                }

                row.style.display = showRow ? "" : "none";
            });
        }

        statusFilter.addEventListener("change", filterTable);
        startDateFilter.addEventListener("input", filterTable);
        endDateFilter.addEventListener("input", filterTable);
    });
</script>


</body>
</html>

