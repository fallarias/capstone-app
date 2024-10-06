<!DOCTYPE html>
<html lang="en">
<head>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
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
            margin-right: -20px;
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
            cursor: pointer;

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
            cursor: pointer;

        }
        input:focus, select:focus {
            border-color: #007bff;
            outline: none;
        }
        .form-container {
            width: 60%;
            margin: auto;
            margin-top: 20px;
            margin-left: 350px;
            padding: 40px;
            padding-right: 60px;
            background-color:white;
            border-radius: 10px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.15);
            
        }
        button[type="submit"] {
            margin-left: 720px;
            background-image: linear-gradient(to right, #28a745, #218838);
            border: none;
            color: #fff;
            padding: 10px 40px;
            border-radius: 10px;
            font-size: 18px;
            transition: background-image 0.4s, transform 0.2s;
            cursor: pointer;
            
        }
        button[type="submit"]:hover {
            background-image: linear-gradient(to right, #218838, #1e7e34);
            transform: translateY(-3px);
            cursor: pointer;

        }
        button[type="submit"]:focus {
            outline: none;
            cursor: pointer;

        }
        .plus-icon {
            color: #28a745;
            cursor: pointer;
            font-size: 50px;

        }
        .plus-icon:hover {
            color: #218838;
        }
        select{
            height: 50px;
            cursor: pointer;

        }
    </style>
</head>
<body>
    @include('components.app-bar')
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
                    window.location.href = '{{ route("admin.dashboard") }}'; // Replace with your actual dashboard route
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
                <input type="file" name="filepath" accept=".pdf" />

                @foreach($data as $item)
                    <div class="form-content">
                        <h3>Step {{ $loop->iteration }}</h3>
                        <input type="hidden" name="data[{{ $loop->index }}][create_id]" value="{{ $item->create_id }}">
                        <label for="office_name_{{ $loop->index }}">Office Name:</label>
                        <input type="text" id="office_name_{{ $loop->index }}" name="data[{{ $loop->index }}][Office_name]" value="{{ $item->Office_name }}" required>

                        <label for="office_task_{{ $loop->index }}">Office Task:</label>
                        <input type="text" id="office_task_{{ $loop->index }}" name="data[{{ $loop->index }}][Office_task]" value="{{ $item->Office_task }}" required>

                        <label for="task_time_{{ $loop->index }}">Allotted Time:</label>
                        <input type="text" id="task_time_{{ $loop->index }}" name="data[{{ $loop->index }}][New_alloted_time]" value="{{ $item->New_alloted_time }}" required>
                    </div>
                @endforeach
            </div>
            <i class="plus-icon fas fa-plus-circle fa-4x" id="add-form"></i>
            <button type="submit">Update</button> 
        </form>
    </div>

    <script>
        let formCount = {{ count($data) }}; // Initialize the form counter based on the existing forms

        // Function to create a new form and add it to the container
        function createForm() {
            formCount++; // Increment the form counter

            const formContent = `
                <div class="form-content">
                    <h3> Step ${formCount} </h3>
                    <i class="minus-icon" onclick="removeForm(this)">-</i>
                    <label for="office_name_${formCount}">Office Name:</label>
                    <select name="office_name[]" id="office_name_${formCount}" required>
                        @foreach($offices as $office)
                            <option value="{{ $office->office_name }}" {{ $item->Office_name === $office->office_name ? 'selected' : '' }}>
                                {{ $office->office_name }}
                            </option>
                        @endforeach
                    </select><br>

                    <label for="office_task_${formCount}">Office Task:</label>
                    <input type="text" name="task[]" id="office_task_${formCount}" required><br>

                    <label for="task_time_${formCount}">Task Allotted Time</label>
                    <select name="time[]" id="task_time_${formCount}" required>
                        @for ($i = 1; $i <= 100; $i++)
                            <option value="{{ $i }}">{{ $i }} hour{{ $i !== 1 ? 's' : '' }}</option>
                        @endfor
                    </select>

                </div>
            `;

            const newFormDiv = document.createElement('div');
            newFormDiv.innerHTML = formContent;

            // Append the new form to the form container with fade-in effect
            document.getElementById('form-container').appendChild(newFormDiv);
            newFormDiv.style.opacity = 0;
            newFormDiv.style.transform = 'translateY(20px)';
            setTimeout(() => {
                newFormDiv.style.opacity = 1;
                newFormDiv.style.transform = 'translateY(0)';
            }, 100);
        }

        // Function to remove a form content
        function removeForm(element) {
            const formContent = element.parentElement;
            formContent.style.opacity = 0;
            formContent.style.transform = 'translateY(-20px)';
            setTimeout(() => formContent.remove(), 300);
        }

        // Add the initial event listener to the plus icon
        document.getElementById('add-form').addEventListener('click', createForm);

        // Prevent form submission if any input is empty
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

            return valid;
        });
    </script>
</body>
</html>
