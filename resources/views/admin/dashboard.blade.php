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
    <style>
.stat-container {
    display: flex;
    margin-top: 20px;
}


.stat-item:hover {
    transform: translateY(-10px);
}

.chart-container {
    margin-top: 30px;
}

.animated-box {
    animation: popUp 0.5s ease-in-out;
}

@keyframes popUp {
    0% {
        transform: scale(0.8);
        opacity: 0;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

        </style>
</head>
<body>
    <!-- App Bar -->
    @include('components.app-bar')
    
    <div class="main-content">
        <!-- Add Code -->
        <h1 class="title">Dashboard</h1>
        <div class="stat-container" style="margin-left: 0px;">
            <div class="stat-item" style="margin-right: 40px;">
                <a href="{{url('/supplier')}}">
                    <h4 style="width: 130px;">Suppliers</h4>
                    <p>{{ $supplier }}</p>
                </a>
            </div>
            <div class="stat-item" style="margin-right: 50px;">
                <a href="{{url('/user')}}">
                    <h4>Users</h4>
                    <p>{{ $user }}</p>
                </a>
            </div>
            <div class="stat-item" style="margin-right: 45px;">
                <a href="{{url('/transaction')}}">
                    <h4 style="width: 130px;">Transactions</h4>
                    <p>{{ $transaction }}</p>
                </a>
            </div>
            <div class="stat-item" style=" display: flex;align-items: center;height: 115px;">
                <a href="{{url('/activated/task')}}">
                    <h4 style="width: 130px;">Activated Task</h4>
                    <p>{{ $activate }}</p>
                </a>
            </div>
        </div>

        <div class="stat-container" style="margin-left: 0px;">
        <div class="chart-container">
            <canvas id="requestChart" width="700" height="200"></canvas>
        </div>
        </div>

            <!-- First Doughnut Chart (Online and Offline Users) -->
        <div class="stat-container1" style="margin-left: 770px; margin-top: -485px;">
        <div class="chart-container ">
            <canvas id="onlineUsersChart"width="190" height="100" ></canvas>
        </div>
        </div>

    <!-- Second Doughnut Chart (Online and Offline Staff) -->
    <div class="stat-container1"  style="margin-left: 1000px; margin-top: -240px;">
        <div class="chart-container">
            <canvas id="onlineStaffChart"width="190" height="100"> </canvas>
        </div>
    </div>

    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const onlineUsersPercentage = 75; // Percentage for online users
        const offlineUsersPercentage = 100 - onlineUsersPercentage;

        const onlineUsersData = {
            labels: ['Online Users', 'Offline Users'], // Text for first chart
            datasets: [{
                data: [onlineUsersPercentage, offlineUsersPercentage],
                backgroundColor: ['#28a745', '#e0e0e0'],
                hoverBackgroundColor: ['#218838', '#d6d6d6'],
            }]
        };

        const onlineUsersConfig = {
            type: 'doughnut',
            data: onlineUsersData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + '%';
                            }
                        }
                    }
                }
            }
        };

        // First Doughnut Chart for Online Users
        const onlineUsersChart = new Chart(
            document.getElementById('onlineUsersChart'),
            onlineUsersConfig
        );


        // Second Doughnut Chart for Online Staff
        const onlineStaffPercentage = 60; // Adjust your online staff percentage
        const offlineStaffPercentage = 100 - onlineStaffPercentage;

        const onlineStaffData = {
            labels: ['Online Staff', 'Offline Staff'], // Updated text for second chart
            datasets: [{
                data: [onlineStaffPercentage, offlineStaffPercentage],
                backgroundColor: ['#18392B', '#e0e0e0'],
                hoverBackgroundColor: ['#18392B', '#d6d6d6'],
            }]
        };

        const onlineStaffConfig = {
            type: 'doughnut',
            data: onlineStaffData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + '%';
                            }
                        }
                    }
                }
            }
        };

        // Second Doughnut Chart for Online Staff
        const onlineStaffChart = new Chart(
            document.getElementById('onlineStaffChart'),
            onlineStaffConfig
        );


        // Line chart for requests per day and week
        const labels = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        const dailyData = [12, 19, 3, 5, 2, 3, 7];
        const weeklyData = [80, 110, 90, 120, 95, 85, 100];

        const data = {
            labels: labels,
            datasets: [{
                label: 'Requests per Day',
                data: dailyData,
                borderColor: 'rgba(40, 167, 69, 0.2)',
                backgroundColor: '#18392B',
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
