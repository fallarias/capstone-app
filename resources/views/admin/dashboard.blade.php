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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4"></script>

    <title>Dashboard</title>
    <style>
        .stat-container{
            display: flex;
            margin-top: 20px;
        }
        

        .stat-item:hover {
            transform: translateY(-10px);
        }

        .chart-container1{
            margin-top: 30px;
        }
        .chart-container2{
            margin-top: -10px;
        }
        .chart-container3{
            margin-top: -130px;
            width: 40%;
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

            90% {
                transform: scale(1);
                opacity: 1;
            }

            80% {
                transform: scale(1);
                opacity: 1;
            }
        }
        .labels {
            display: flex;
            flex-direction: column;
            justify-content: center; /* Center align the text vertically */
            font-size: 16px; /* Font size for labels */
        }
        .labels div {
            margin: 5px 0; /* Space between labels */
        }
        .user-container {
  width: 100%;
  max-width: 800px;
  margin: auto;
  font-family: Arial, sans-serif;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
}



.user-container {
  width: 72%;
  height: 10%;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.4); /* Adds a shadow */
  margin-left: 0px;
  margin-top: -250px;
  margin-bottom: 200px;
  border-radius: 10px;
  background-color: #f9f9f9;

}

.header {
    margin-top: -15px;
  animation: zoomIn 0.5s ease forwards;
  text-align: center;
  padding: 20px;
}

@keyframes zoomIn {
  from { transform: scale(0); opacity: 0; }
  to { transform: scale(1); opacity: 1; }
}





.user-list {
  display: flex;
  flex-direction: column;
  max-height: 400px;  /* Set the maximum height for the container */
  overflow-y: auto;   /* Enable vertical scrolling */
  overflow-x: hidden;  /* Disable horizontal scrolling */
  width: 100%;         /* Adjust the width if necessary */
  border: 1px solid #ccc; /* Optional: Add a border to indicate the scrollable area */
}

.user-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 10px;
  border-bottom: 1px solid #ccc;
  width: 100%;  /* Ensure the user item takes the full width of the container */
}

.profile-pic {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  margin-right: 10px;
}

.user-info {
  flex-grow: 1;
  margin-left: 10px;
}

.activity {
  display: flex;
  align-items: center;
}

.online-status {
  color: green;
}

.online-circle {
  display: inline-block;
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background-color: green;
  margin-right: 5px;
  margin-left: -100px;
}



.add-user-btn {
  display: flex;
  align-items: center;
  padding: 8px 12px;
  background-color: #f0f0f0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.add-user-btn {
  transition: box-shadow 0.3s ease;
}

.add-user-btn:hover {
    background-color: #45a049; /* Slightly darker green on hover */
    box-shadow: 0 5px 15px rgba(0, 128, 0, 0.5); /* Dark green shadow */
}


.add-user-btn {
    font-weight:bold;
    text-align: center;
    text-decoration:none;
    color: #fff;
    background-color: #28a745;
    border: 2px solid #000;
    border-radius: 10px;
    box-shadow: 5px 5px 0px #000;
    transition: all 0.3s ease;
}

.add-user-btn i.icon {
  font-size: 20px;
  margin-right: 10px; /* Space between icon and text */
}

.add-user-btn:hover {
    background-color: #fff;
    color: green;
    border: 2px solid #28a745;
    box-shadow: 5px 5px 0px #28a745;/* Darker green on hover */
}

.add-user-btn:active {
    background-color: #28a745;
    box-shadow: none;
    transform: traslateY(4px);/* Even darker on click */
}


.chart-container {
            width: 300px;
            margin: auto;
            text-align: center;
        }
        .legend {
            list-style: none;
            padding: 0;
            margin-top: 10px;
        }
        .legend li {
            display: flex;
            align-items: center;
            font-size: 14px;
            margin: 5px 0;
        }
        .legend span {
            width: 12px;
            height: 12px;
            display: inline-block;
            margin-right: 8px;
            border-radius: 50%;
        }





    </style>
</head>
<body>

<div class="container">
    
    <!-- App Bar -->
    <div id="app-bar-container">
        @include('components.app-bar', ['admin' => $admin])
    </div>
    <div style="text-align: center; margin-bottom: 60px; margin-left:1200px;margin-bottom:-130px;margin-top:40px;">
        @if($admin)
            <span style="color: black;text-align:start;">
                <p class="text-color">Welcome, {{ $admin->firstname }} </p>
                <p style="font-size:15px; color:darkgray;font-weight:500">Greate work and have a nice day! </p>
            </span>
        @else
            <span style="color: white; font-size: 18px;">Admin Name Not Available</span>
        @endif
    </div>

    <div class="main-content">
        <!-- Add Code -->
        <h1 class="title" style="margin-bottom: 10px;">Dashboard</h1>


        @include('admin.autoReload')
        <div class="stat-container" style="margin-left: 0px;">
            <div class="chart-container1" style="margin-top:-10px">
                <canvas id="lineChart" width="700" height="200"></canvas>
            </div>
        </div>

        <div class="stat-container3" >
    <div class="chart-container3">
        <canvas id="reviewChart" style="margin-bottom:-20px; margin-top: 60px;"></canvas>
    </div>
    <div class="legend-container" style=" ">

        <ul style="list-style: none; margin-left: -220px;margin-top: -20px;">
            @foreach($departments as $department)
                <li style="color: #36A2EB;">{{ $department }}</li>
            @endforeach
        </ul>

        </div>
    </div>



<div class="stat-container1" style=" display: flex; align-items: center; margin-left:770px;margin-top: -550px;">
    <div class="chart-container1" style="margin-left:62px;margin-right:62px;margin-top:-62px;margin-bottom:-10px;">
        <canvas id="userAllPieChart" width="285" height="190"></canvas> <!-- New Canvas for Pie Chart -->
    </div>
</div>

<div class="stat-container2" style="margin-left:580px; margin-top: 15px;">
    <div class="chart-container1">
        <canvas id="barChart" width="600" height="200"></canvas>
    </div>
</div>

<div class="user-container">
  <div class="header">
    <h2>Users</h2>
    <p id="user-count">{{ $users }} registered users</p>
    <button class="add-user-btn"  onclick="window.location='{{ route('admin.createTaskPage') }}'">
      <i class="icon">+</i> Add new user
    </button>
  </div>

  <div class="user-list">
    @forelse($online as $counter)
      <div class="user-item">
        <img class="profile-pic" src="path-to-profile1.jpg" alt="User profile picture">
        
        <div class="user-info">
          <h3>{{$counter->email}}</h3>
          <p>{{$counter->login_date}}</p>
        </div>
        
        <div class="activity">
          <span class="online-status">
            <span class="online-circle"></span> Online
          </span>
        </div>
      </div>
    @empty
      <p>No User Online</p>
    @endforelse
  </div>
</div>

</div>


    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>




<script>
// Check if the chart has been initialized to prevent multiple initializations
let chartInitialized = false;

document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
});

function initializeCharts() {
    // Only initialize the chart if it hasn't been initialized yet
    if (chartInitialized) return;

    var ctx = document.getElementById("reviewChart").getContext("2d");

    // Check if the context is valid before initializing the chart
    if (!ctx) {
        console.error("Failed to get canvas context");
        return;
    }

    // Define the chart data
    const data = {
        labels: {!! json_encode($departments) !!},
        datasets: [{
            data: {!! json_encode($stars) !!}, // Count of ratings
            backgroundColor: ["#36A2EB", "#FFCE56", "#FF6384", "#4BC0C0", "#9966FF"]
        }]
    };

    // Initialize the chart
    const reviewChart = new Chart(ctx, {
        type: "doughnut",
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false  // Hide default legend
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ": " + tooltipItem.raw + " votes"; // Display count in tooltip
                        }
                    }
                }
            }
        }
    });

    // Set the flag to true after the chart is initialized
    chartInitialized = true;
}
</script>


<script>

    
const bar = document.getElementById('barChart').getContext('2d');
const barChart = new Chart(bar, {
    type: 'bar',
    data: {
        labels: @json($label), // Combine daily and weekly labels
        datasets: [
            {
                label: 'Completion',
                data: @json($data), // Daily counts
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: ['#28a745', '#3DED97', '#90EE90', '#AEF359', '#9BC53A'],
                fill: true,
                spanGaps: true,
            },
        ]
    },
    options: {
        indexAxis: 'y', // This makes the bar chart horizontal
        responsive: true,
        scales: {
            x: { // X-axis now represents the counts
                beginAtZero: true,
                max: 100,
            },
            y: { // Y-axis will display the labels
                beginAtZero: true,
            }
        }
    }
});

</script>



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
                            return tooltipItem.label + ': ' + tooltipItem.raw ;
                        }
                    }
                }
            }
        }
    };

    const line = document.getElementById('lineChart').getContext('2d');
        const lineChart = new Chart(line, {
            type: 'bar',
            data: {
                labels: @json($dailyLabels), // Combine daily and weekly labels
                datasets: [
                    {
                        label: 'Request per Day',
                        data: @json($dailyValues), // Daily counts
                        borderColor: '#18392B',
                        backgroundColor: '#18392B',
                        fill: true,
                        spanGaps: true,
                    },
                    // {
                    //     label: 'Request per Week',
                    //     data: @json($weeklyValues), // Weekly counts
                    //     borderColor: '#28a745',
                    //     backgroundColor: '#28a745',
                    //     fill: true,
                    //     spanGaps: true,
                    // }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });


</script>

<script>


    const allUser = document.getElementById('userAllPieChart').getContext('2d');
    const userAllPieChart = new Chart(allUser, {
    type: 'pie',
    data: {
        labels: ['All Client', 'All Staff', 'All Admin'],
        datasets: [{
            data: [{{ json_encode($client) }}, {{ json_encode($staff) }}, {{ json_encode($admins) }}],
            backgroundColor: ['#28a745', '#18392B', '#9BC53A'],
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'left',
                labels: {
                    top: 600,
                    padding: 20, // Increase space between the chart and legend labels
                    boxWidth: 15, // Width of the colored box next to labels
                }

            },
            title: {
                display: true,
                text: 'Total Number of Users',
                position: 'top', // You can use 'top', 'left', 'bottom', 'right'
                padding: {
                    top: 60, // Adjust top padding
                    bottom: 0 // Adjust bottom padding
                }
            }
        }
    }
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




<script>
    // Function to reload the app-bar component only
    function refreshAppBar() {
        fetch("/app-bar") // Replace with the actual route that loads the app-bar
            .then(response => response.text())
            .then(html => {
                document.getElementById("app-bar-container").innerHTML = html;
            })
            .catch(error => console.log("Error loading app-bar:", error));
    }

    // Refresh every 60 seconds (adjust interval as needed)
    setInterval(refreshAppBar, 5000); 
</script>
</body>
</html>
