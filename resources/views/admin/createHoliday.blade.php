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

        .line3 {
            height: 2px;
            background-color: #04ad2b;
            margin: 20px 0;
            width: 102%;
            margin-left:-32px;
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
            })
        </script>
    @endif

    @include('components.app-bar', ['admin' => $admin])
    
    <div class="main-content">
        <h1 class="title4">Create Holiday</h1>

        <form class="description" method="POST" action="{{ route('admin.holidays') }}">
            @csrf
            @method('post')
            <label class="description-text" for="Name">Description:</label>
            <textarea name="desc" id="desc" rows="6" required>{{ old('desc') }}</textarea>
            <label class="sched" for="date">Holiday Date</label>
            <input type="date" name="date" class="date-input" required>
            <button type="submit" class="btn4" style="margin-top: 5px;">Save</button>
        </form>
    </div>

    <div class="line1"></div>

    <div id="calendar"></div>

    
    
    <div class="holiday-table">
    <div class="table-responsive">
        <table class="listTable" id="logTable" border="1">
        <thead>
            <tr>
                <th><label><input type="checkbox" id="selectAllCheckboxHeader">Select all</label></th>
                <th>Description</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="holidayList">
            @foreach ($holidays as $holiday)
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
    if (window.calendarInitialized) return; // Prevent reinitialization
    window.calendarInitialized = true;

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
