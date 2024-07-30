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
			<th>Time In</th>
            <th>Time Out</th>
            <th>Staff ID</th>

		</thead>
		<tbody>
            @forelse($qrcode as $counter => $row)
                <tr>
                    <td>{{ $counter + 1}}</td> 
                    <td>{{ $row->time_in }}</td>
                    <td>{{ $row->time_out }}</td>
                    <td>{{ $row->staff_id }}</td>


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