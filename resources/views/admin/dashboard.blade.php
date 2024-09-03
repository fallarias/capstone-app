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
    <title>Dashboard</title>

</head>
<body>
    <!-- App Bar -->
    
    @include('components.app-bar')
    <!-- Main Content -->
    <div class="main-content">
        <h1 class="title">Dashboard</h1>
        <div class="stat-container">
            <div class="stat-item" style="margin-right: 40px;">
                <a href="{{url('/supplier')}}">
                    <h4>Suppliers</h4>
                    <p>{{ $supplier }}</p>
                </a>
            </div>
            <div class="stat-item" style="margin-right: 50px;">
                <a href="{{url('/user')}}">
                    <h4>Users</h4>
                    <p>{{ $user }}</p>
                </a>
            </div>
            <div class="stat-item" style="margin-right: 20px;">
                <a href="{{url('/transaction')}}">
                    <h4 style="width: 160px;">Transactions</h4>
                    <p>{{ $transaction }}</p>
                </a>
            </div>
            <div class="stat-item">
                <a href="{{url('/clients')}}">
                    <h4>Clients</h4>
                    <p>{{ $client }}</p>
                </a>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="requestChart" width="680" height="200"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labels = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']; // Example day labels
        const dailyData = [12, 19, 3, 5, 2, 3, 7]; // Replace with your daily request data
        const weeklyData = [80, 110, 90, 120, 95, 85, 100]; // Replace with your weekly request data

        const data = {
            labels: labels,
            datasets: [{
                label: 'Requests per Day',
                data: dailyData,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.2)',
                fill: true,
                tension: 0.1
            }, {
                label: 'Requests per Week',
                data: weeklyData,
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.2)',
                fill: true,
                tension: 0.1
            }]
        };

        const config = {
            type: 'line',
            data: data,
            options: {
                scales: {
                    x: {
                        beginAtZero: true
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        const requestChart = new Chart(
            document.getElementById('requestChart'),
            config
        );
    </script>
</body>
</html>
