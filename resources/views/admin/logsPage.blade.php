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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    <title>Document</title>
</head>
<body>
@include('components.app-bar')
<div style="display: flex; justify-content: center; margin-top: 40px; width:1000px; margin-left:400px">
<table border = "1">
		<thead>
			<th>#</th>
            <th>User ID</th>
			<th>Action</th>
            <th>Account Type</th>
            <th>Date</th>
	
		</thead>
		<tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ $loop->iteration}}</td> 
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