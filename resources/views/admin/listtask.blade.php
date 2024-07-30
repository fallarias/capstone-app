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
			<th>Office Name</th>
			<th>Office Task</th>
			<th>New Alloted Time</th>
		</thead>
		<tbody>
        @forelse($data as $counter => $row)
            <tr>
                <td>{{ $counter + 1}}</td> 
                <td>{{ $row->Office_name }}</td>
                <td>{{ $row->Office_task }}</td>
                <td>{{ $row->New_alloted_time }}</td>
                <td>
                    <a href="{{ route('admin.edit', ['id' => $row->create_id]) }}">edit</a></td>
            <td>
                <form action='{{route('admin.delete', $row->create_id)}}' method="POST">
                    @csrf
                    <button type="submit">delete</button>
                    </form>
                </td>
                

            </tr>
            @empty
                <tr>
                    <td colspan="5">No Users Found</td>
                </tr>
        @endforelse
        
		</tbody>
	</table>
    <a href="{{url('/dashboard')}}">Back</a>
</body>
</html>