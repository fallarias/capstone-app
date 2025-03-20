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
        .chart-container4{
            margin-top: 20px;
            width: 100%;
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


        .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        }



.user-container {
  width: 55%;
  max-height: 300px; /* Limits height */
  overflow-y: auto; /* Enables scrolling if content exceeds max height */
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.4); /* Adds a shadow */
  margin-left: 770px;
  margin-top: -610px;
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


        #openModalButton {
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
        }



        /* Modal container */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        /* Modal content box */
        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            max-height: 80%; /* Max height as a percentage of the viewport */
            overflow-y: auto; 
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
                    window.location.href = '{{ route("admin.dashboard") }}'; // Replace with your actual dashboard route
                }
            });
        </script>
    @endif
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
        <div class="legend-container" >

        <ul style="list-style: none; margin-left: -220px;margin-top: -20px;">
            @foreach($departments as $department)
                <li style="color: #005733;">{{ $department }}</li>
            @endforeach
        </ul>

        </div>
    </div>

    <div class="stat-container2" style="margin-left:0px; margin-top: -295px;">
        <div class="chart-container4">
            <canvas id="barChart" width="600" height="200"></canvas>
        </div>
    </div>



<div class="user-container">
  <div class="header">
    <h2>Users</h2>
    <p id="user-count">{{ $users }} registered users</p>
    <button class="add-user-btn" id="openModalButton">
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
      <p style="text-align: center;">No User Online</p>
    @endforelse
  </div>
</div>

</div>



<div id="inputModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h1 class="title1" style="margin-left: 110px; font-size:28px">Create New Office</h1>
        <form id="inputForm" method="POST" action="{{ route('admin.newOfficeAccounts') }}">
        @csrf
            <div id="modal-form-container" class="form-container">
                <!-- Forms will be dynamically added here -->
            </div>
            <!-- Button to add more forms -->
            <button type="button" class="submit-btn" style="background-color: #005733;" id="modal-add-form">Add User</button>
            <button type="submit" name="btnsave" class="submit-btn" style="margin-top: 10px; background-color: #005733;">Save</button>
        </form>

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
    if (chartInitialized) return;

    var ctx = document.getElementById("reviewChart").getContext("2d");
    if (!ctx) {
        console.error("Failed to get canvas context");
        return;
    }

    // Get data from Laravel blade
    const departments = {!! json_encode($departments) !!};
    const stars = {!! json_encode($stars) !!};

    // Check if all values in `stars` are zero or the array is empty
    const isEmptyData = stars.length === 0 || stars.every(value => value === 0);

    // Set default empty data when there are no votes
    const chartData = isEmptyData
        ? {
            labels: ["No Data"],
            datasets: [{
                data: [1], // Placeholder value
                backgroundColor: ["#E0E0E0"], // Light gray color to indicate no data
            }]
        }
        : {
            labels: departments,
            datasets: [{
                data: stars,
                backgroundColor: ["#005733", "#FFCE56", "#FF6384", "#4BC0C0", "#9966FF"]
            }]
        };

    // Initialize the chart
    const reviewChart = new Chart(ctx, {
        type: "doughnut",
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function (tooltipItem) {
                            // Hide tooltip for empty data case
                            if (isEmptyData) return "";
                            return tooltipItem.label + ": " + tooltipItem.raw + " votes";
                        }
                    }
                }
            }
        }
    });

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


<script>
        // Get modal and button elements
        var modal = document.getElementById("inputModal");
        var btn = document.getElementById("openModalButton");
        var closeBtn = document.querySelector(".close");

        // Show modal when button is clicked
        btn.onclick = function() {
            modal.style.display = "flex";
        }

        // Close modal when "x" is clicked
        closeBtn.onclick = function() {
            modal.style.display = "none";
        }

        // Close modal when user clicks outside the modal content
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>


<script>
    let formCounts = 0; // Counter to give unique IDs to each form element
    let stepCounts = 0;

    // Function to create a new form and add it to the container
    function createForms(first = '', middle = '', last = '', email = '', password = '', department = '') {
        formCounts++;
        stepCounts++;

        const formContents = `
          <div class="form-content" id="form_${formCounts}">
            <button class="close-icon" onclick="removeForm(${formCounts})">&times;</button>
            <div class="form-group">
                <input type="text" name="first[]" id="first_${formCounts}" value="${first}" required>
                <label class="labelForm2">Firstname</label>
            </div>
            <div class="form-group">
                <input type="text" name="middle[]" id="middle_${formCounts}" value="${middle}" required>
                <label class="labelForm2">Middlename</label>
            </div>
            <div class="form-group">
                <input type="text" name="last[]" id="last_${formCounts}" value="${last}" required>
                <label class="labelForm2">Lastname</label>
            </div>
            <div class="form-group">
                <input type="text" name="email[]" id="email_${formCounts}" value="${email}" required>
                <label class="labelForm2">Email</label>
            </div>
            <div class="form-group">
                <input type="password" name="password[]" id="password_${formCounts}" value="${password}" required>
                <label class="labelForm2">Password</label>
            </div>
            <div class="form-group">
                <input type="text" name="department[]" id="department_${formCounts}" value="${department}" required>
                <label class="labelForm2">Office Name</label>
            </div>
          </div>
        `;

        // Create a new div and set its inner HTML to the new form content
        const newFormDiv = document.createElement('div');
        newFormDiv.innerHTML = formContents;

        // Append the new form to the modal's form container
        document.getElementById('modal-form-container').appendChild(newFormDiv);
    }

    // Function to remove a form
    function removeForm(formId) {
        const formToRemove = document.getElementById(`form_${formId}`);
        if (formToRemove) formToRemove.remove();
        stepCounts--; // Decrement step count
    }

    // Add the event listener to the modal's add form button
    document.getElementById('modal-add-form').addEventListener('click', function () {
        createForms(); // Call createForm without passing any arguments
    });
    


    // Re-populate old values when the page loads
    document.addEventListener('DOMContentLoaded', function () {
        const firsts = @json(old('first', []));
        const middles = @json(old('middle', []));
        const lasts = @json(old('last', []));
        const emails = @json(old('email', []));
        const passwords = @json(old('password', []));
        const departments = @json(old('department', []));
        
        firsts.forEach((first, index) => {
            const middle = middles[index] || '';
            const last = lasts[index] || '';
            const email = emails[index] || '';
            const password = passwords[index] || '';
            const department = departments[index] || '';
            createForms(first, middle, last, email, password, department);
        });
    });
</script>
</body>
</html>
