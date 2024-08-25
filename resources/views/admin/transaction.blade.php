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
			<th>Staff ID</th>
            <th>Client ID</th>
            <th>Status</th>

		</thead>
		<tbody>
            @forelse($transaction as $counter => $row)
                <tr>
                    <td>{{ $loop->iteration}}</td> 
                    <td>{{ $row->staff_id }}</td>
                    <td>{{ $row->client_id }}</td>
                    <td>{{ $row->status }}</td>


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