<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creating Holiday</title>

    <!-- FullCalendar CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>

    <style>
        .title3 {
            font-size: 60px;
            margin-left: 5px;
            margin-top: 15px;
            font-weight: bolder;
            font-family: 'Courier New', Courier, monospace;
            background: linear-gradient(90deg, #005733, #04ad2b, #2acb4f);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        label {
            display: block;
            font-size: 16px;
            font-weight: 600;
            margin: 10px 0 5px;
        }

        textarea, input[type="date"] {
            width: 310%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .btn4 {
            background: #04ad2b;
            color: #fff;
            font-weight: bold;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn4:hover {
            background: #028c1f;
        }

        .holiday-list {
            text-align: center;
            margin: 30px auto;
        }

        .line1 {
            height: 400px;
            background-color: #04ad2b;
            margin: 20px 0;
            width: 2px;
            margin-left: 1030px;
            margin-top: -300px;
        }
        .line2 {
            height: 2px;
            background-color: #04ad2b;
            margin: 20px 0;
            width: 160%;
            margin-left:-32px;
        }
        .line3 {
            height: 2px;
            background-color: #04ad2b;
            margin: 20px 0;
            width: 102%;
            margin-left:-32px;
        }

        #calendar {
            max-width: 100%;
            margin: 500px ;
            margin-bottom: -60px;
            margin-left:1090px;
            margin-top:-410px;
            margin-right:30px;
        }

        .fc-header-toolbar {
            background-color: #04ad2b;
            color: white;
            border-radius: 5px;
            padding: 5px;
        }

        .fc-daygrid-day-top {
            color: #04ad2b; 
        }

        .fc-event {
            background-color: #04ad2b;
            border-color: #028c1f;
            color: white;
        }

        .fc-daygrid-day-frame {
            border: 1px solid #04ad2b; 
        }

        .fc-day-today {
            background-color: rgba(4, 173, 43, 0.2); 
        }

        .fc-day-sat, .fc-day-sun {
            background-color: rgba(4, 173, 43, 0.1);
        }
        .fc .fc-col-header-cell {
                color: black !important; 
                background-color: #04ad2b; 
                font-weight: bold;
            }

        .fc-daygrid-day-number {
            color: black; 
            font-weight: bold;
        }

        .btn.deleteBtn {
            border: none;
            background: none;
            padding: 5px; 
            font-size: 16px; 
            cursor: pointer; 
            color: #dc3545; 
        }

        .btn.deleteBtn:hover {
            color: #ff4d4d; 
        }

        td {
            padding: 0; 
            text-align: center; 
        }

        td .fas {
            font-size: 20px; 
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
                    window.location.href = '{{ route("admin.listOfTaskPage") }}';
                }
            });
        </script>
    @endif

    @include('components.app-bar', ['admin' => $admin])
    
    <div class="main-content">
        <h1 class="title3">Create Holiday</h1>
        <div class="line2"></div>

        <form style="margin-left: 25px;" method="POST" action="{{ route('admin.holidays') }}">
            @csrf
            @method('post')
            <label for="Name">Description:</label>
            <textarea name="desc" id="desc" rows="6" required>{{ old('desc') }}</textarea>
            <label for="date">School Holiday Date</label>
            <input type="date" name="date" required>
            <button type="submit" class="btn4" style="margin-top: 5px;">Save</button>
        </form>
    </div>

    <div class="line1"></div>

    <div id="calendar"></div>

    <div class="line3" style="margin-top: 100px;"></div>
    
    
<div style="text-align: start; margin-bottom: 120px; margin-left:300px; margin-top:40px; margin-right:30px;">
    <label>
        <input type="checkbox" id="selectAllCheckbox" style="margin-bottom: 10px;"> view all
    </label>
    <table class="table table-striped">
        <thead>
            <tr>
                <th><input type="checkbox" id="selectAllCheckboxHeader">Select all</th>
                <th>Description</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="holidayList">
            @foreach ($holidays->take(5) as $holiday)
                <tr>
                    <td><input type="checkbox" class="holidayCheckbox"></td>
                    <td>{{ $holiday->description }}</td>
                    <td>{{ $holiday->holiday_date }}</td>
                    <td>
                        <button class="btn btn-danger deleteBtn" data-id="{{ $holiday->id }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    // Toggle Select All checkboxes
    document.getElementById('selectAllCheckboxHeader').addEventListener('change', function () {
        const checkboxes = document.querySelectorAll('.holidayCheckbox');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });

    // Delete holiday entry
    document.querySelectorAll('.deleteBtn').forEach(button => {
        button.addEventListener('click', function () {
            const holidayId = this.getAttribute('data-id');
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to delete this holiday?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Make a request to delete the holiday
                    window.location.href = `/delete-holiday/${holidayId}`;
                }
            });
        });
    });
</script>




    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: [
                    @foreach ($holidays as $holiday)
                    {
                        title: '{{ $holiday->description }}',
                        start: '{{ $holiday->holiday_date }}',
                    },
                    @endforeach
                ]
            });
            calendar.render();
        });
    </script>
</body>
</html>
