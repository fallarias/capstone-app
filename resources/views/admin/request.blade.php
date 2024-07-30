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
            <th>Supplier Middlename</th>
            <th>Supplier Lastname</th>
            <th>Address</th>
			<th>Type of Service</th>	
            <th>Service Description</th>
            <th>User ID</th>	
		</thead>
		<tbody>
            @forelse($request as $counter => $row)
                <tr>								
                    <td>{{ $counter + 1}}</td> 
                    <td>{{ $row->client_lname }}</td>
                    <td>{{ $row->client_fname }}</td>
                    <td>{{ $row->client_mname }}</td>
                    <td>{{ $row->Office_use}}</td>
                    <td>{{ $row->Request_type}}</td>
                    <td>{{ $row->Reason_of_request }}</td>
                    <td>{{ $row->client_id }}</td>
                    <td>{{ $row->transaction_id }}</td>
                    <td>{{ $row->supplier_id }}</td>

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