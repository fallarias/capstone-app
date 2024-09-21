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
    <title>Creating New Task</title>
    <style>
        .form-content {
            margin-bottom: 10px;
            width: fit-content;
            padding: 20px;
            border: 1px solid #ccc;
            position: relative;
        }
        .plus-icon, .minus-icon {
            font-size: 24px;
            cursor: pointer;
            display: block;
            margin-top: 20px;
        }
        .minus-icon {
            position: absolute;
            top: 5px;
            right: 10px;
            color: red;
        }
        input, select {
            width: 20%;
            height: 100%;
        }
        input {
            font-size: medium;
        }
    </style>
</head>
<body>
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

    @include('components.app-bar')
    <div style="display: flex; justify-content: center; margin-top: 40px; width:1000px; margin-left:400px">
    <div>
        <form id="task-form" action="{{ route('admin.update', $task->task_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="form-container" class="form-container">
                <label for="">Name</label>
                <input type="text" value="{{$data_task->name}}" name="task_name"><br>
                <input type="file" name="filepath" accept=".pdf" />
                
                @foreach($data as $item)
                    <div class="form-content">

                        <h3>Step {{$loop->iteration}}</h3>
                        <input type="hidden" name="data[{{ $loop->index }}][create_id]" value="{{ $item->create_id }}">
                        <label for="office_name">Office Name:</label>
                        <input type="text" id="office_name_{{ $loop->index }}" name="data[{{ $loop->index }}][Office_name]" value="{{ $item->Office_name }}" required>

                        <label for="Office_task">Office Task:</label>
                        <input type="text" id="office_task_{{ $loop->index }}" name="data[{{ $loop->index }}][Office_task]" value="{{ $item->Office_task }}" required>

                        <label for="New_alloted_time">Allotted Time:</label>
                        <input type="text" id="task_time_{{ $loop->index }}" name="data[{{ $loop->index }}][New_alloted_time]" value="{{ $item->New_alloted_time }}" required><br>
                    </div>
                @endforeach
            </div>
            <i class="plus-icon" id="add-form">+</i>
            <button type="submit" style="margin-bottom:100px;">Update</button>
        </form>
    </div>
    </div>

    <script>
        let formCount = {{ count($data) }}; // Initialize the form counter based on the existing forms

        // Function to create a new form and add it to the container
        function createForm() {
            formCount++; // Increment the form counter

            const formContent = `
                <div class="form-content">
                    <i class="minus-icon" onclick="removeForm(this)">-</i>
                    <label for="office_name_${formCount}">Office Name:</label>
                    <select name="data[${formCount}][Office_name]" id="office_name_${formCount}" required>
                        <option value="" disabled selected>Select Office</option>
                        <option value="EO Office">EO Office</option>
                        <option value="Procurement Office">Procurement Office</option>
                        <option value="Budget Office">Budget Office</option>
                        <option value="Accounting Office">Accounting Office</option>
                        <option value="Supplies Office">Supplies Office</option>
                    </select><br>

                    <label for="office_task_${formCount}">Office Task:</label>
                    <input type="text" name="data[${formCount}][Office_task]" id="office_task_${formCount}" required><br>

                    <label for="task_time_${formCount}">Allotted Time:</label>
                    <input type="text" name="data[${formCount}][New_alloted_time]" id="task_time_${formCount}" required>
                </div>
            `;

            const newFormDiv = document.createElement('div');
            newFormDiv.innerHTML = formContent;

            // Append the new form to the form container
            document.getElementById('form-container').appendChild(newFormDiv);
        }

        // Function to remove a form content
        function removeForm(element) {
            element.parentElement.remove();
        }

        // Add the initial event listener to the first plus icon
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
