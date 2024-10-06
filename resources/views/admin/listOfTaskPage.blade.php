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
<div class="container">
<div style="text-align:center; margin-top: 50px;">
    @include('components.app-bar') 

    <div style="display: flex; justify-content: center; margin-top: -10px; width:1000px; margin-left:400px">
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
                <th>No.</th>
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
                        <td style="font-weight: bold;">{{ $loop->iteration }}</td>
                        <td>{{ $task->name }}</td>
                        <td>{{ $task->date }}</td>

                        <td style="display: flex; gap: 20px; justify-content: center; padding: 10px;">
                            <form action="{{ route('admin.editTaskPage', $task->task_id) }}" method="GET">
                                @csrf
                                <button type="submit" style="background-color: #4CAF50; color: white; border: none; padding: 13px 20px; border-radius: 5px; cursor: pointer; transition: background-color 0.3s ease;width:120px">
                                    Edit
                                </button>
                            </form>
                            <form action="{{ route('admin.taskActivate', $task->task_id) }}" method="POST">
                                @csrf
                                <button type="submit" style="background-color: #0275d8; color: white; border: none; padding: 13px 20px; border-radius: 5px; cursor: pointer; transition: background-color 0.3s ease;width:120px">
                                    Activate
                                </button>
                            </form>
                            <form action="{{ route('admin.deleteTask', $task->task_id) }}" method="POST">
                                @csrf
                                <button type="submit" style="background-color: #d9534f; color: white; border: none; padding: 13px 20px; border-radius: 5px; cursor: pointer; transition: background-color 0.3s ease;">
                                    Deactivate
                                </button>
                            </form>
                        </td>

                        <td style="padding: 10px;">
                            @forelse($task->files as $file)
                                @if($file->thumbnailUrl)
                                    <a href="{{ $file->pdfUrl }}" target="_blank" style="text-decoration: none;">
                                        <img src="{{ $file->thumbnailUrl }}" alt="PDF Thumbnail" style="width: 100px; height: auto; border: 1px solid #ccc; border-radius: 5px; padding: 5px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); cursor: pointer; transition: transform 0.3s ease;">
                                    </a>
                                @else
                                    <a href="{{ $file->pdfUrl }}" target="_blank" style="text-decoration: none;">
                                        <i class="fas fa-file-pdf" style="font-size: 35px; color: #d9534f; cursor: pointer; margin:auto;padding: 5px; transition: transform 0.3s ease; border-radius: 5px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);"></i>
                                    </a>
                                @endif  
                            @empty
                                <p style="color: #777; font-style: italic;">No files for this task.</p>
                            @endforelse
                        </td>

                        <td>
                            {{$file->filename}}
                        </td>
                        <td>
                            @if($task->status == 0)
                                <p style="color: red;font-weight:bold">Deactivate</p>
                            @elseif ($task->status == 1)
                                <p style="color: blue;font-weight:bold">Activated</p>
                            @else
                                <p style="color: gray;font-weight:bold">None</p>
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
</div>
</div>
</body>
</html>
