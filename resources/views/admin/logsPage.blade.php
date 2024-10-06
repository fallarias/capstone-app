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
    <title>Document</title>
</head>
<body>

<div style="text-align:center">
    @include('components.app-bar') 

    <!-- Filter Form -->
    <div style="display: flex; justify-content: center; margin-top: 20px; margin-bottom: 20px;">
    <form action="{{ route('admin.logsPage') }}" method="GET" style="display: flex; gap: 15px; align-items: center; margin-bottom: 20px;">
    <!-- Custom CSS Styles -->
    <style>
        .form-control {
            width: 250px;
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-control:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 6px 12px rgba(0, 123, 255, 0.2);
        }

        select.form-control {
            width: 200px;
            height: 40px;
        }

        button.btn-primary {
            background-color: #007bff;
            margin-top: -20px;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        button.btn-primary:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        input.form-control:hover, select.form-control:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        input.form-control {
            width: 280px; /* Adjusting width for a larger search bar */
        }
    </style>

    <!-- Search Task Name -->
    <input type="text" name="task_name" class="form-control" placeholder="Search User Name" 
           value="{{ request('task_name') }}" title="Search by task name">

    <!-- Date Dropdown -->
    <select name="date" class="form-control" title="Filter by date">
        <option value="">Select Date</option>
        <option value="2023-01-01" {{ request('date') == '2023-01-01' ? 'selected' : '' }}>2023-01-01</option>
        <option value="2023-02-01" {{ request('date') == '2023-02-01' ? 'selected' : '' }}>2023-02-01</option>
        <!-- Add more date options as needed -->
    </select>

    <!-- Filter Button -->
    <button type="submit" class="btn btn-primary">Filter</button>
</form>

    </div>
<div style="display: flex; justify-content: center; margin-top: -10px; width:1000px; margin-left:400px">
<table border = "1">
		<thead>
			<th>No.</th>
            <th>User ID</th>
			<th>Action</th>
            <th>Account Type</th>
            <th>Date</th>
	
		</thead>
		<tbody>
            @forelse($logs as $log)
                <tr>
                    <td style="font-weight: bold; text-align:center">{{ $loop->iteration}}</td> 
                    <td>{{ $log->user_id }}</td>
                    <td>{{ $log->action }}</td>
                    <td>{{ $log->account_type }}</td>
                    <td>{{ $log->Date }}</td>
                </tr>
                @empty
                    <tr>
                        <td colspan="8">No Logs Found</td>
                    </tr>
            @endforelse
		</tbody>
	</table><br><br>
</body>
</html>