<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <title>Creating New Task</title>
    <style>
        body {
            background-color: #f4f7fc;
            font-family: 'Arial', sans-serif;
        }
        .form-content {
            margin-bottom: 20px;
            padding: 20px;
            width: auto;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            transition: transform 0.3s, opacity 0.3s;
        }
        .form-content:hover {
            transform: scale(1.02);
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.15);
        }
        .plus-icon, .minus-icon {
            font-size: 28px;
            cursor: pointer;
            margin-top: 10px;
        }
        .minus-icon {
            position: absolute;
            top: 0px;
            right: 15px;
            color: red;
            transition: color 0.3s;
        }
        .minus-icon:hover {
            color: #ff3333;
        }
        input, select {
            width: 97%;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            transition: border-color 0.3s;
        }
        input:focus, select:focus {
            border-color: #007bff;
            outline: none;
        }
        .form-container {
            width: 60%;
            
            margin: 30px 350px;
            padding: 40px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.15);
        }
        button[type="submit"] {
            background-image: linear-gradient(to right, #28a745, #218838);
            border: none;
            color: #fff;
            padding: 10px 40px;
            border-radius: 10px;
            font-size: 18px;
            margin-left: 720px;
            transition: background-image 0.4s, transform 0.2s;
        }
        button[type="submit"]:hover {
            background-image: linear-gradient(to right, #218838, #1e7e34);
            transform: translateY(-3px);
        }
        .plus-icon {
            color: #28a745;
            font-size: 50px;
        }
        .plus-icon:hover {
            color: #218838;
        }
        select {
            height: 50px;
        }
    </style>
</head>
<body>
    @include('components.app-bar', ['admin' => $admin])

    @if($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: @json($errors->first()),
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Great...',
                text: @json(session('success')),
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route("admin.dashboard") }}';
                }
            });
        </script>
    @endif

    <div class="form-container">
        <form id="task-form" action="{{ route('admin.update', $task->task_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="form-container">
                <label for="">Task Name</label>
                <input type="text" value="{{ $data_task->name }}" name="task_name" required><br>
                <input type="file" name="filepath" accept=".docx,.docs,.lxs,.xlsx">

                @foreach($data as $item)
                    <div class="form-content">
                        <h3>Step {{ $loop->iteration }}</h3>
                        <input type="hidden" name="data[{{ $loop->index }}][create_id]" value="{{ $item->create_id }}">
                        <label for="office_name_{{ $loop->index }}">Office Name:</label>
                        <select name="data[{{ $loop->index }}][Office_name]" id="office_name_{{ $loop->index }}" required>
                            @foreach($offices as $office)
                                <option value="{{ $office->department }}" 
                                    {{ $item->Office_name == $office->department ? 'selected' : '' }}>
                                    {{ $office->department }}
                                </option>
                            @endforeach
                        </select>

                        <label for="office_task_{{ $loop->index }}">Office Task:</label>
                        <input type="text" id="office_task_{{ $loop->index }}" name="data[{{ $loop->index }}][Office_task]" value="{{ $item->Office_task }}" required>

                        <label for="task_time_{{ $loop->index }}">Allotted Time:</label>
                            <select id="task_time_{{ $loop->index }}" name="data[{{ $loop->index }}][New_alloted_time]" required>
                                <optgroup label="Minutes">
                                    @for ($i = 1; $i <= 59; $i++)
                                        <option value="{{ $i }} minute{{ $i !== 1 ? 's' : '' }}" 
                                            {{ $item->New_alloted_time_display == "$i minutes" ? 'selected' : '' }}>
                                            {{ $i }} {{ $i !== 1 ? 'minutes' : 'minute' }}
                                        </option>
                                    @endfor
                                </optgroup>
                                <optgroup label="Hours">
                                    @for ($i = 1; $i <= 23; $i++)
                                        <option value="{{ $i }} hour{{ $i !== 1 ? 's' : '' }}" 
                                            {{ $item->New_alloted_time_display == "$i hours" ? 'selected' : '' }}>
                                            {{ $i }} {{ $i !== 1 ? 'hours' : 'hour' }}
                                        </option>
                                    @endfor
                                </optgroup>
                                <optgroup label="Days">
                                    @for ($i = 1; $i <= 30; $i++)
                                        <option value="{{ $i }} day{{ $i !== 1 ? 's' : '' }}" 
                                            {{ $item->New_alloted_time_display == "$i days" ? 'selected' : '' }}>
                                            {{ $i }} {{ $i !== 1 ? 'days' : 'day' }}
                                        </option>
                                    @endfor
                                </optgroup>
                                <optgroup label="Weeks">
                                    @for ($i = 1; $i <= 52; $i++)
                                        <option value="{{ $i }} week{{ $i !== 1 ? 's' : '' }}" 
                                            {{ $item->New_alloted_time_display == "$i weeks" ? 'selected' : '' }}>
                                            {{ $i }} {{ $i !== 1 ? 'weeks' : 'week' }}
                                        </option>
                                    @endfor
                                </optgroup>
                            </select>

                    </div>
                @endforeach
            </div>
            <i class="plus-icon fas fa-plus-circle" id="add-form"></i>
            <button type="submit">Update</button> 
        </form>
    </div>

    <script>
        let formCount = {{ count($data) }}; 

function createForm() {
    formCount++;
    const formContent = `
        <div class="form-content">
            <i class="minus-icon fas fa-minus-circle" onclick="removeForm(this)"></i>
            <label for="office_name_${formCount}">Office Name:</label>
            <select name="office_name[]" id="office_name_${formCount}" required>
                 @foreach($offices as $office)
                    <option value="{{ $office->department }}">{{ $office->department }}</option>
                @endforeach
            </select><br>
            <label for="office_task_${formCount}">Office Task:</label>
            <input type="text" name="task[]" id="office_task_${formCount}" required><br>
            <label for="task_time_${formCount}">Task Allotted Time:</label>
            <select name="time[]" id="task_time_${formCount}" required>
                <optgroup label="Minutes">
                    @for ($i = 1; $i <= 60; $i++)
                        <option value="{{ $i }} minute{{ $i !== 1 ? 's' : '' }}">{{ $i }} minute{{ $i !== 1 ? 's' : '' }}</option>
                    @endfor
                </optgroup>
                <optgroup label="Hours">
                    @for ($i = 1; $i <= 24; $i++)
                        <option value="{{ $i }} hour{{ $i !== 1 ? 's' : '' }}">{{ $i }} hour{{ $i !== 1 ? 's' : '' }}</option>
                    @endfor
                </optgroup>
                <optgroup label="Days">
                    @for ($i = 1; $i <= 30; $i++)
                        <option value="{{ $i }} day{{ $i !== 1 ? 's' : '' }}">{{ $i }} day{{ $i !== 1 ? 's' : '' }}</option>
                    @endfor
                </optgroup>
                <optgroup label="Weeks">
                    @for ($i = 1; $i <= 52; $i++)
                        <option value="{{ $i }} week{{ $i !== 1 ? 's' : '' }}">{{ $i }} week{{ $i !== 1 ? 's' : '' }}</option>
                    @endfor
                </optgroup>
            </select>

        </div>
    `;
    const newFormDiv = document.createElement('div');
    newFormDiv.innerHTML = formContent;
    document.getElementById('form-container').appendChild(newFormDiv);
}


        function removeForm(element) {
            const formContent = element.parentElement;
            formContent.style.opacity = 0;
            formContent.style.transform = 'translateY(-20px)';
            setTimeout(() => formContent.remove(), 300);
        }

        document.getElementById('add-form').addEventListener('click', createForm);

        document.getElementById('task-form').addEventListener('submit', function(event) {
            const inputs = document.querySelectorAll('input[required], select[required]');
            let valid = true;

            inputs.forEach(input => {
                if (!input.value.trim()) {
                    valid = false;
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please fill out all required fields before submitting.',
                        confirmButtonText: 'OK'
                    });
                    event.preventDefault();
                    return;
                }
            });
        });
    </script>
<script>
    // Function to refresh the stats every 5 seconds
    function refreshStats() {
        $.ajax({
            url: '/audit', // The route where you fetch updated stats
            method: 'GET',
            success: function(response) {
                // The data is fetched but not used for updating the page.
                console.log(response); // Optional: Log the data to the console for debugging
            }
        });
    }

    // Refresh the stats every 5 seconds (5000 milliseconds)
    setInterval(refreshStats, 30000);
</script>
</body>
</html>
