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
            width: 200%;
            max-width: 1200px;
            margin: 20px auto;
            margin-left: 300px;
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
            max-width: 1300px;
            margin: 20px auto;
            margin-left:270px;
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
            background-color:rgb(181, 188, 185);
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
            background-color:#28a745;
            z-index: 2;
        }
        .progress-step .circle1 {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-block;
            line-height: 40px;
            color: white;
            font-weight: bold;
            background-color:#BBBCB6;
            z-index: 2;
        }

        /* Completed step */
        .progress-step.finished .circle1 {
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

        /* Wrapper for the progress bar */
.progress-bar-wrapper {
    display: flex;
    width: 100%;
    height: 8px;
    background-color: #BBBCB6; /* Default gray */
    margin-bottom: 20px;
    position: relative;
    border-radius: 4px;
    overflow: hidden;
}

/* Green bar (completed part) */
.progress-bar-completed {
    background-color: #28a745; /* Green for completed */
    height: 100%;
    transition: width 0.3s ease-in-out;
}

/* Gray bar (remaining part) */
.progress-bar-remaining {
    background-color: #BBBCB6; /* Gray for remaining */
    height: 100%;
    transition: width 0.3s ease-in-out;
}



/* Green progress bar when step 1 is completed and step 2 is ongoing */
.progress-container .progress-between-steps {
    position: absolute;
    top: 18px;
    left: 75px;
    height: 8px;
    background-color: #28a745; /* Default is green */
    z-index: 0;
    transition: background-color 0.3s ease-in-out;
}

.truncate-text {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100px; /* Adjust width as needed */
    display: inline-block;
}







        
    </style>
</head>
<body>
@include('components.clientDrawer')

<!-- Order Information Section -->
<div class="order-info">
    <div>
        <p>Transaction Name: {{$name->name}}</p>
            @foreach ($beyondFour as $four)

                {{$four->office_name}}: Accepted the document beyond 5 PM <br>

            @endforeach
    </div>
    <div class="order-status">
        <p class="completion">Complete <span style="color:#ff6b6b;" id="completion-percentage">0%</span></p>
    </div>
</div>

<!-- Progress Bar Section -->
<div class="progress-container">
    @foreach ($task as $index => $taskItem)
        <div class="progress-step {{ $taskItem->task_status == 'Completed' ? 'Completed' : ($taskItem->task_status == 'Ongoing' ? 'Ongoing' : '') }}">
            <div class="{{ $taskItem->task_status == 'Completed' ? 'circle ' : ($taskItem->task_status == 'Ongoing' ? 'circle' : 'circle1') }}">
                @if($taskItem->task_status == 'Completed')
                    <i class="fas fa-check"></i>
                @else
                    {{ $index + 1 }}
                @endif
            </div>
            <div class="task-details">
                <strong class="truncate-text">{{ $taskItem->Office_name }}</strong><br>
                Task: {{ $taskItem->Office_task }}<br>
                Allotted Time: {{ $taskItem->New_alloted_time_display }}<br>
                Status: {{ $taskItem->task_status }}<br>
                {{ $taskItem->accepted }}
            </div>

        </div>

        <!-- Dynamically Add Progress Bar Between Steps -->
        @if($index < count($task) - 1)
            <div class="progress-between-steps" id="progressBetween-{{ $index }}"></div>
        @endif
    @endforeach
</div>



<script>
document.addEventListener('DOMContentLoaded', function () {
    const tasks = @json($task); // Get Laravel task data

    // Loop through each task step except the last one
    for (let i = 0; i < tasks.length - 1; i++) {
        const step1 = tasks[i];
        const step2 = tasks[i + 1];
        const step3 = tasks[i + 2];
        const step4 = tasks[i + 3];
        const step5 = tasks[i + 4];
        const step6 = tasks[i + 5];
        const step7 = tasks[i + 6];
        const step8 = tasks[i + 7];
        const step9 = tasks[i + 8];
        const progressBetween = document.getElementById(`progressBetween-${i}`);

        if (!progressBetween) continue; // Skip if the element is not found

        // Reset progress bar color to default (gray) before applying conditions
        progressBetween.style.backgroundColor = '#BBBCB6'; // Default gray
        progressBetween.style.width = '10px'; // Default width

        // Handle the logic based on the task status combination for different lengths
        if (tasks.length >= 2) {
            // Handle for 2 steps
            if (step1.task_status === 'Ongoing' && step2.task_status === 'Waiting') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '550px';
            } else if (step1.task_status === 'Completed' && step2.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '1130px';
            } else if (step1.task_status === 'Completed' && step2.task_status === 'Completed') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '1130px';
            }
        }

        if (tasks.length >= 3) {
            // Handle for 3 steps
            if (step1.task_status === 'Completed' && step2.task_status === 'Completed' && step3.task_status === 'Completed') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '1130px'; // Triple complete condition
            }
              else if (step1.task_status === 'Completed' && step2.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '850px';
            } else if (step1.task_status === 'Completed' && step2.task_status === 'Completed') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '1130px';
            } else if (step1.task_status === 'Ongoing' && step2.task_status === 'Waiting') {
                progressBetween.style.backgroundColor = '#28a745'; // Yellow
                progressBetween.style.width = '270px';
            }
        }

        if (tasks.length >= 4) {
            // Handle for 4 steps
            if (step1.task_status === 'Completed' && step2.task_status === 'Completed' && step3.task_status === 'Completed' && step4.task_status === 'Completed') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '1130px'; // four complete condition
            }
              else if (step1.task_status === 'Completed' && step2.task_status === 'Completed' && step3.task_status === 'Completed' && step4.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '1130px'; // Triple complete condition
            }
            
              else if (step1.task_status === 'Completed' && step2.task_status === 'Completed' && step3.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '940px'; // Triple complete condition
            }
              else if (step1.task_status === 'Completed' && step2.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '565px';

            } else if (step1.task_status === 'Ongoing' && step2.task_status === 'Waiting') {
                progressBetween.style.backgroundColor = '#28a745'; // Yellow
                progressBetween.style.width = '180px';
            }
        }

        if (tasks.length >= 5) {
            // Handle for 5 steps
            if (step1.task_status === 'Completed' && step2.task_status === 'Completed' && step3.task_status === 'Completed' && step4.task_status === 'Completed'  && step5.task_status === 'Completed') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '1130px'; // four complete condition
            }
              else if (step1.task_status === 'Completed' && step2.task_status === 'Completed' && step3.task_status === 'Completed' && step4.task_status === 'Completed' && step5.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '1130px'; // Triple complete condition
            }
              else if (step1.task_status === 'Completed' && step2.task_status === 'Completed' && step3.task_status === 'Completed' && step4.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '990px'; // Triple complete condition
            }
              else if (step1.task_status === 'Completed' && step2.task_status === 'Completed' && step3.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '708px'; // Triple complete condition
            }
              else if (step1.task_status === 'Completed' && step2.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '430px';

            } else if (step1.task_status === 'Ongoing' && step2.task_status === 'Waiting') {
                progressBetween.style.backgroundColor = '#28a745'; // Yellow
                progressBetween.style.width = '140px';
            }
        }

        if (tasks.length === 6) {
            // Handle for 6 steps
            if (step1.task_status === 'Completed' && step2.task_status === 'Completed' && step3.task_status === 'Completed' && step4.task_status === 'Completed'  && step5.task_status === 'Completed' && step6.task_status === 'Completed') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '1130px'; // four complete condition
            }
              else if (step1.task_status === 'Completed' && step2.task_status === 'Completed' && step3.task_status === 'Completed' && step4.task_status === 'Completed'  && step5.task_status === 'Completed' && step6.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '1130px'; // four complete condition
            }
              else if (step1.task_status === 'Completed' && step2.task_status === 'Completed' && step3.task_status === 'Completed' && step4.task_status === 'Completed' && step5.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '1010px'; // Triple complete condition
            }
              else if (step1.task_status === 'Completed' && step2.task_status === 'Completed' && step3.task_status === 'Completed' && step4.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '790px'; // Triple complete condition
            }
              else if (step1.task_status === 'Completed' && step2.task_status === 'Completed' && step3.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '555px'; // Triple complete condition
            }
              else if (step1.task_status === 'Completed' && step2.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '340px';

            } else if (step1.task_status === 'Ongoing' && step2.task_status === 'Waiting') {
                progressBetween.style.backgroundColor = '#28a745'; // Yellow
                progressBetween.style.width = '120px';
            }
        }

        if (tasks.length === 7) {
            if (step1.task_status === 'Completed' && step2.task_status === 'Completed' && step3.task_status === 'Completed' && step4.task_status === 'Completed'  && step5.task_status === 'Completed' && step6.task_status === 'Completed'&& step7.task_status === 'Complete') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '1130px'; // four complete condition
            }
              else if (step1.task_status === 'Completed' && step2.task_status === 'Completed' && step3.task_status === 'Completed' && step4.task_status === 'Completed'  && step5.task_status === 'Completed' && step6.task_status === 'Completed'&& step7.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '1130px'; // four complete condition
            }
              else if (step1.task_status === 'Completed' && step2.task_status === 'Completed' && step3.task_status === 'Completed' && step4.task_status === 'Completed'  && step5.task_status === 'Completed' && step6.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '1030px'; // four complete condition
            }
              else if (step1.task_status === 'Completed' && step2.task_status === 'Completed' && step3.task_status === 'Completed' && step4.task_status === 'Completed' && step5.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '840px'; // Triple complete condition
            }
              else if (step1.task_status === 'Completed' && step2.task_status === 'Completed' && step3.task_status === 'Completed' && step4.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '650px'; // Triple complete condition
            }
              else if (step1.task_status === 'Completed' && step2.task_status === 'Completed' && step3.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '465px'; // Triple complete condition
            }
              else if (step1.task_status === 'Completed' && step2.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '280px';

            } else if (step1.task_status === 'Ongoing' && step2.task_status === 'Waiting') {
                progressBetween.style.backgroundColor = '#28a745'; // Yellow
                progressBetween.style.width = '100px';
            }
        }


        if (tasks.length === 8) {
            // Handle for 6 steps
            if (step1.task_status === 'Completed' && step2.task_status === 'Completed' && step3.task_status === 'Completed' && step4.task_status === 'Completed'  && step5.task_status === 'Completed' && step6.task_status === 'Completed'&& step7.task_status === 'Complete'&& step8.task_status === 'Completed') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '1130px'; // four complete condition
            }
              else if (step1.task_status === 'Completed' && step2.task_status === 'Completed' && step3.task_status === 'Completed' && step4.task_status === 'Completed'  && step5.task_status === 'Completed' && step6.task_status === 'Completed'&& step7.task_status === 'Complete'&& step8.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '1130px'; // four complete condition
            }
              else if (step1.task_status === 'Completed' && step2.task_status === 'Completed' && step3.task_status === 'Completed' && step4.task_status === 'Completed'  && step5.task_status === 'Completed' && step6.task_status === 'Completed'&& step7.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '1040px'; // four complete condition
            }
              else if (step1.task_status === 'Completed' && step2.task_status === 'Completed' && step3.task_status === 'Completed' && step4.task_status === 'Completed'  && step5.task_status === 'Completed' && step6.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '880px'; // four complete condition
            }
              else if (step1.task_status === 'Completed' && step2.task_status === 'Completed' && step3.task_status === 'Completed' && step4.task_status === 'Completed' && step5.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '720px'; // Triple complete condition
            }
              else if (step1.task_status === 'Completed' && step2.task_status === 'Completed' && step3.task_status === 'Completed' && step4.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '560px'; // Triple complete condition
            }
              else if (step1.task_status === 'Completed' && step2.task_status === 'Completed' && step3.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '395px'; // Triple complete condition
            }
              else if (step1.task_status === 'Completed' && step2.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '240px';

            } else if (step1.task_status === 'Ongoing' && step2.task_status === 'Waiting') {
                progressBetween.style.backgroundColor = '#28a745'; // Yellow
                progressBetween.style.width = '80px';
            }
        }


        if (tasks.length === 9) {
            // Handle for 6 steps
            if (step1.task_status === 'Completed' && step2.task_status === 'Completed' && step3.task_status === 'Completed' && step4.task_status === 'Completed'  && step5.task_status === 'Completed' && step6.task_status === 'Completed'&& step7.task_status === 'Complete'&& step8.task_status === 'Completed'&& step9.task_status === 'Completed') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '1130px'; // four complete condition
            }
              else if (step1.task_status === 'Completed' && step2.task_status === 'Completed' && step3.task_status === 'Completed' && step4.task_status === 'Completed'  && step5.task_status === 'Completed' && step6.task_status === 'Completed'&& step7.task_status === 'Complete'&& step8.task_status === 'Completed'&& step9.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '1130px'; // four complete condition
            }
              else if (step1.task_status === 'Completed' && step2.task_status === 'Completed' && step3.task_status === 'Completed' && step4.task_status === 'Completed'  && step5.task_status === 'Completed' && step6.task_status === 'Completed'&& step7.task_status === 'Complete'&& step8.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '1000px'; // four complete condition
            }
              else if (step1.task_status === 'Completed' && step2.task_status === 'Completed' && step3.task_status === 'Completed' && step4.task_status === 'Completed'  && step5.task_status === 'Completed' && step6.task_status === 'Completed'&& step7.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '910px'; // four complete condition
            }
              else if (step1.task_status === 'Completed' && step2.task_status === 'Completed' && step3.task_status === 'Completed' && step4.task_status === 'Completed'  && step5.task_status === 'Completed' && step6.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '775px'; // four complete condition
            }
              else if (step1.task_status === 'Completed' && step2.task_status === 'Completed' && step3.task_status === 'Completed' && step4.task_status === 'Completed' && step5.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '630px'; // Triple complete condition
            }
              else if (step1.task_status === 'Completed' && step2.task_status === 'Completed' && step3.task_status === 'Completed' && step4.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '490px'; // Triple complete condition
            }
              else if (step1.task_status === 'Completed' && step2.task_status === 'Completed' && step3.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '350px'; // Triple complete condition
            }
              else if (step1.task_status === 'Completed' && step2.task_status === 'Ongoing') {
                progressBetween.style.backgroundColor = '#28a745'; // Green
                progressBetween.style.width = '210px';

            } else if (step1.task_status === 'Ongoing' && step2.task_status === 'Waiting') {
                progressBetween.style.backgroundColor = '#28a745'; // Yellow
                progressBetween.style.width = '70px';
            }
        }

        // Continue adding similar conditions for 6 steps if needed.
    }
});
</script>







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
 

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Pass task data from Laravel to JavaScript
    const tasks = @json($task); // Laravel data passed to JavaScript
    const totalTasks = tasks.length;
    const completedTasks = tasks.filter(task => task.task_status === 'Completed').length;

    // Calculate percentages
    const completedPercentage = totalTasks > 0 ? (completedTasks / totalTasks) * 100 : 0;
    const remainingPercentage = 100 - completedPercentage;

    // Update progress bar widths
    document.querySelector('.progress-bar-completed').style.width = `${completedPercentage}%`;
    document.querySelector('.progress-bar-remaining').style.width = `${remainingPercentage}%`;

    // Display the completion percentage in the HTML
    document.getElementById('completion-percentage').textContent = `${completedPercentage.toFixed(0)}%`;
});

</script>

</body>
</html>









