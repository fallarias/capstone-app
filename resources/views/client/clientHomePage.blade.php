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

        /* Optional: Styles for Main Content */
        .container {
            margin-top: 20px;
            margin-left: 100px;
            padding: 20px;
            max-width: 1400px; /* Set max-width to make it wider */
            width: 100%; /* Full width */
        }

        .dashboard-container {
            display: flex;
            gap: 10px; /* Reduced gap to make the layout more compact */
            margin-top: 20px;
            flex-wrap: wrap;
            margin-left: 130px;
        }

        .dashboard-card {
            text-decoration: none;
            color: #18392B;
            width:300px;
            height: 100px;
            padding: 10px; /* Reduced padding */
            margin-left: 20px;
            text-align: start;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }
        .dashboard-card:hover {
            text-decoration: none;
            color: #00b894;

        }


        .client-card{
            font-size: 200px;
        }


        .dashboard-card1 {
            width:955px; /* Adjusted width to fit four cards within 300px */
            padding: 10px; /* Reduced padding */
            margin-left: 150px;
            text-align: start;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .dashboard-title {
            color: #00b894;
            font-size: 34px;
            font-weight: bold;
            margin-top: -60px;
            text-align: start;
            margin-left: 150px;
            font-family: 'Open Sans', sans-serif;
        }


        /* Adjust font size in cards to keep content readable */
        .dashboard-card h1 {
            font-size: 26px;
            color: #00b894;
            margin-left:40px;
        }

        .dashboard-card p {
            font-size: 20px;
            margin-top:10px; 
            margin-left:10px;
        }

        .app-bar .nav-links a {
    color: white;
    text-decoration: none;
    font-size: 16px;
    margin-bottom: -5px;
}

.app-bar .nav-links a:hover {
    text-decoration: none; /* Ensure no underline appears */
}



    </style>
</head>
<body>


    <div class="app-bar">
        <!--
    <div class="search-container">
        <form class="form-inline" action="/search" method="GET">
            <input type="text" name="query" class="form-control mr-sm-2" placeholder="Search" aria-label="Search" style="margin-left:1100px">
            <button class="btn custom-search-button" type="submit" >
                <i class="fas fa-search" style="font-size: 20px;"></i> Search
            </button>
        </form>
        </div>

        
    -->



    </div>


    <!-- SweetAlert Scripts -->
    @if($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: @json($errors->first()),
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Great...',
                text: @json(session('success')),
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ url("login") }}';
                }
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: @json(session('error')),
                confirmButtonText: 'OK'
            })
        </script>
    @endif

    @include('components.clientDrawer', ['client' => $client])

    <!-- Main Content -->
    <!-- Main Content -->
    <div style="display: flex; justify-content: center; margin-top: 40px;max-width:1400">
        <div class="container">
            <h2 class="dashboard-title">Status Overview</h2>
            <div class="dashboard-container">
                <a href="{{ url('/client/task/list') }}" class="dashboard-card">
                    <h1 >{{ $documents }}</h1>
                    <p style="">Available Document</p>
                </a>
                <a href="{{ url('/client/notification') }}" class="dashboard-card">
                    <h1>{{ $messages }}</h1>
                    <p>Message</p>
                </a>
                <a href="{{ url('/client/task/list') }}" class="dashboard-card">
                    <h1>{{ $pending }}</h1>
                    <p>Pending</p>
                </a>
                <a href="{{ url('/client/transaction') }}" class="dashboard-card">
                    <h1>{{ $complete }}</h1>
                    <p>Completed</p>
                </a>
            </div>

            <!-- <div class="dashboard-card1">
                <div style="display: flex; justify-content: center; margin-top: 40px;">
                    <div class="container">
                        <h2 class="dashboard-title">Weekly request Chart</h2>
                        <canvas id="weeklyAreaChart" width="400" height="100"></canvas>
                    </div>
                </div>
            </div> -->
        </div>
    </div>


    <script>
        // Data and configuration for the area chart
        const ctx = document.getElementById('weeklyAreaChart').getContext('2d');
        const weeklyAreaChart = new Chart(ctx, {
            type: 'line', // Use 'line' type to create an area chart with fill enabled
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 'Week 6', 'Week 7'], // Weekly labels
                datasets: [{
                    label: 'Data Value',
                    data: [20, 40, 35, 60, 80, 70, 90], // Example data points ranging from 0 to 100
                    backgroundColor: 'rgba(54, 162, 235, 0.2)', // Background color for the area
                    borderColor: 'rgba(54, 162, 235, 1)', // Line color
                    borderWidth: 2,
                    fill: true // Enable fill to create an area chart
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            max: 100 // Setting maximum range to 100
                        }
                    }]
                }
            }
        });
    </script>
</body>
</html>
