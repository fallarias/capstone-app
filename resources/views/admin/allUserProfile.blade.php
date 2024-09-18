<!DOCTYPE html>
<html lang="en">
<head>
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
@include('components.app-bar')
<div style="display: flex; justify-content: center; margin-top: 40px; width:1000px; margin-left:400px">

<table border = "1">
		<thead>
			<th>#</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Middle Name</th>
			<th>Email</th>
            <th>Account Type</th>
            <th>Password</th>
            <th>Profile Picture</th>
	
		</thead>
		<tbody>
            @forelse($user as $counter => $row)
                <tr>
                    <td>{{ $loop->iteration}}</td> 
                    <td>{{ $row->firstname }}</td>
                    <td>{{ $row->lastname }}</td>
                    <td>{{ $row->middlename }}</td>
                    <td>{{ $row->email }}</td>
                    <td>{{ $row->account_type }}</td>
                    <td>{{ $row->password }}</td>
                    <td>{{ $row->profile_picture }}</td>
                </tr>
                @empty
                    <tr>
                        <td colspan="8">No Users Found</td>
                    </tr>
            @endforelse
		</tbody>
	</table><br>
    <a href="{{url('/dashboard')}}">Back</a>
    </div>
</body>
</html>