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


@if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Great...',
                text: @json(session('success')),
                confirmButtonText: 'OK'
            });
        </script>
    @endif

<table border = "1px ">
		<thead>
			<th>#</th>
			<th>Task</th>
			<th>Date Created</th>
		</thead>
		<tbody>
        @forelse($data as $counter => $row)

            <tr>
                <td>{{ $loop->iteration}}</td> 
                <td>{{ $row->name }}</td>
                <td>{{ $row->date }}</td>

                <td>
                    <a href="{{ route('admin.editTaskPage', ['id' => $row->task_id]) }}">edit</a>
                </td>

                <td>
                    <form action='{{route('admin.taskActivate', $row->task_id)}}' method="POST">
                        @csrf
                        <button type="submit">Activate</button>
                    </form>
                </td>

                <td>
                    <form action='{{route('admin.deleteTask', $row->task_id)}}' method="POST">
                        @csrf
                        <button type="submit">Deactivate</button>
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
</div>
    <a href="{{url('/dashboard')}}">Back</a>
</body>
</html>