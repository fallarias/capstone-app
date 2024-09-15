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
			<th>Email</th>
            <th>Account Type</th>
            <th>Password</th>
	
		</thead>
		<tbody>
            @forelse($user as $counter => $row)
                <tr>
                    <td>{{ $loop->iteration}}</td> 
                    <td>{{ $row->email }}</td>
                    <td>{{ $row->account_type }}</td>
                    <td>{{ $row->password }}</td>
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