<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4"></script>
    <style>
        /* Order info container */
        .order-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
            padding-bottom: 10px;
            border-bottom: 1px solid #ccc;
        }

        /* Completion text */
        .completion {
            font-weight: bold;
            font-size: 18px;
            color: #333;
        }

        .expected-completion {
            font-size: 12px;
            color: #666;
        }

        /* Progress bar container */
        .progress-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 800px;
            margin: 20px auto;
            position: relative;
        }

        /* Connecting line between circles */
        .progress-container::before {
            content: '';
            position: absolute;
            top: 18px;
            left: 75px;
            right: 75px;
            height: 8px;
            background-color: rgba(0, 128, 0, 0.5);
            z-index: 0;
        }

        /* Each progress step */
        .progress-step {
            position: relative;
            text-align: center;
            width: 150px;
            color: #666;
            z-index: 1;
        }

        /* Circle */
        .progress-step .circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-block;
            line-height: 40px;
            color: white;
            font-weight: bold;
            background-color: rgba(0, 150, 0, 1);
            z-index: 2;
        }

        /* Completed step */
        .progress-step.finished .circle {
            background-color: #28a745;
        }

        .progress-step .circle i {
            font-size: 18px;
        }

        /* Task details */
        .task-details {
            font-size: 12px;
            color: #333;
            margin-top: 8px;
        }
    </style>
</head>
<body>
@include('components.clientDrawer')

<!-- Order Information Section -->
<div class="order-info">
    <div>
        <p>Transaction Name: {{$name->name}}</p>
    </div>
    <div class="order-status">
        <p class="completion">Complete <span style="color:#ff6b6b;" id="completion-percentage">0%</span></p>
    </div>
</div>

<!-- Progress Bar Section -->
<div class="progress-container">
    @foreach ($task as $index => $taskItem)
        <div class="progress-step {{ $taskItem->task_status == 'Completed' ? 'Completed' : '' }}">
            <div class="circle">
                @if($taskItem->task_status == 'Completed')
                    <i class="fas fa-check"></i>
                @else
                    {{ $index + 1 }}
                @endif
            </div>
            <div class="task-details">
                <strong>{{ $taskItem->Office_name }}</strong><br>
                Task: {{ $taskItem->Office_task }}<br>
                Deadline: {{ $taskItem->New_alloted_time }}<br>
                Status: {{ $taskItem->task_status }}
            </div>
        </div>
    @endforeach
</div>

<!-- JavaScript to calculate the completion percentage -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Pass the task data from Laravel to JavaScript
        const tasks = @json($task); // Laravel data passed to JavaScript
        const totalTasks = tasks.length;
        const completedTasks = tasks.filter(task => task.task_status === 'Completed').length;
        
        // Calculate completion percentage
        const completionPercentage = totalTasks > 0 ? (completedTasks / totalTasks) * 100 : 0;

        // Display the completion percentage in the HTML
        document.getElementById('completion-percentage').textContent = `${completionPercentage.toFixed(0)}%`;
    });
</script>

</body>
</html>
