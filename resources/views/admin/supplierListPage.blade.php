<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

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
</body>
</html>