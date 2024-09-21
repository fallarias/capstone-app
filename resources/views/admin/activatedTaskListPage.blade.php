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
                <th>Template</th>
                <th>Template Name</th>
            </thead>
            <tbody>
                @forelse($data as $task)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $task->name }}</td>
                        <td>{{ $task->date }}</td>
                        <td style="display: flex; justify-content: center; align-items: center; text-align: center;">
                            @forelse($task->files as $file)
                                @if($file->pdfUrl)
                                    <a href="{{ $file->pdfUrl }}" target="_blank">
                                        <i class="fas fa-file-pdf" style="font-size: 70px; cursor: pointer;"></i>
                                    </a>
                                @else
                                    <p>No PDF available.</p>
                                @endif
                            @empty
                                <p>No files for this task.</p>
                            @endforelse
                        </td>
                        <td>
                            {{$file->filename}}
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
