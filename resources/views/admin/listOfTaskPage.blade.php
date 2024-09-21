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
        <style>
            th, td { text-align: center; }
            button { font-size: 17px; }
        </style>

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

        <table border="1px">
            <thead>
                <th>#</th>
                <th>Task</th>
                <th>Date Created</th>
                <th>Actions</th>
                <th>Template</th>
                <th>Template Name</th>
                <th>Status</th>
            </thead>
            <tbody>
                @forelse($data as $task)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $task->name }}</td>
                        <td>{{ $task->date }}</td>

                        <td style="display: flex; gap: 20px; justify-content: center;">
                            <form action="{{ route('admin.editTaskPage', $task->task_id) }}" method="GET">
                                @csrf
                                <button type="submit">Edit</button>
                            </form>
                            <form action="{{ route('admin.taskActivate', $task->task_id) }}" method="POST">
                                @csrf
                                <button type="submit">Activate</button>
                            </form>
                            <form action="{{ route('admin.deleteTask', $task->task_id) }}" method="POST">
                                @csrf
                                <button type="submit">Deactivate</button>
                            </form>
                        </td>

                        <td>
                            @forelse($task->files as $file)
                            @if($file->thumbnailUrl)
                                <a href="{{ $file->pdfUrl }}" target="_blank">
                                    <img src="{{ $file->thumbnailUrl }}" alt="PDF Thumbnail" style="width: 70px; cursor: pointer;">
                                </a>
                            @else
                                <a href="{{ $file->pdfUrl }}" target="_blank">
                                    <i class="fas fa-file-pdf" style="font-size: 70px; cursor: pointer;"></i>
                                </a>
                            @endif  
                            @empty
                                <p>No files for this task.</p>
                            @endforelse
                        </td>
                        <td>
                            {{$file->filename}}
                        </td>
                        <td>
                            @if($task->status == 0)
                                <p>Deactivate</p>
                            @elseif ($task->status == 1)
                                <p>Activated</p>
                            @else
                                <p>None</p>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No tasks found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <a href="{{ url('/dashboard') }}">Back</a>
</body>
</html>
