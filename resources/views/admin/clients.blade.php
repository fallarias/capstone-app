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
			<th>Client ID</th>
	
		</thead>
		<tbody>
            @forelse($clients as $counter => $row)
                <tr>
                    <td>{{ $counter + 1}}</td> 
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