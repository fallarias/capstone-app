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
@include('components.app-bar')
<div style="display: flex; justify-content: center; margin-top: 40px; width:1000px; margin-left:400px">
<table border = "1">
		<thead>
			<th>#</th>
			<th>Supplier Firstname</th>
            <th>Supplier Middle name</th>
            <th>Supplier Lastname</th>
            <th>Address</th>
			<th>Type of Service</th>	
            <th>Service Description</th>
            <th>User ID</th>	
		</thead>
		<tbody>
            @forelse($supplier as $counter => $row)
                <tr>
                    <td>{{$loop->iteration}}</td> 
                    <td>{{ $row->supplier_fname }}</td>
                    <td>{{ $row->supplier_mname }}</td>
                    <td>{{ $row->supplier_lname }}</td>
                    <td>{{ $row->address}}</td>
                    <td>{{ $row->type_of_service}}</td>
                    <td>{{ $row->service_desc }}</td>
                    <td>{{ $row->user_id }}</td>

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