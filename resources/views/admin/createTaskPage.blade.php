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
        .form-container {
            margin-top: 20px;
        }

        .form-content {
            width: 75%;
            position: relative;
            padding: 20px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .close-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            margin : auto;
            background-color: white;
            color: red;
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            text-align: center;
            line-height: 25px;
            cursor: pointer;
            font-size: 30px;
        }



        .plus-icon{
            font-size: 24px;
            cursor: pointer;
        }

        .plus-icon {
            display: block;
            margin-top: 20px;
            margin-right: 700px;
        }



        input, select {
            width: 13%;
            height: 100%;
            font-size: medium;
        }



        .main-content {
            max-width: 600px;
            margin-left: 300px;
            margin-top: 30px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
        }
        .title1 {
            margin-left: -550px;
            text-align: center;
            font-size: 60px;
            margin-bottom: 20px;
        }
        label, input {
            width: 95%;
            margin: auto;
            display: block;
            margin-bottom: 10px;
        }
        input[type="text"], input[type="file"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .btn3, .btn2, .btn-clear {
            width: 49%;
            background-color: #18392B;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn3:hover, .btn2:hover {
            background-color: #555;
        }

        .btn-clear:hover {
            background-color: red;
        }

        .btn4{
            width: 101%;
            background-color: #18392B;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn4:hover{
            background-color: #28a745;
        }

        .plus-icon {
            display: inline-block;
            background-color: #28a745; /* Green background */
            color: white; /* White plus icon color */
            border-radius: 50%; /* Makes the icon a circle */
            width: 40px; /* Circle width */
            height: 40px; /* Circle height */
            text-align: center; /* Center the plus icon */
            line-height: 40px; /* Vertically center the plus icon */
            font-size: 24px; /* Font size for the plus icon */
            cursor: pointer; /* Pointer cursor on hover */
        }
        .plus-icon:hover {
            background-color: #218838; /* Darker green on hover */
        }
        select {
            width: 100%; /* Makes the select take up the full width of its container */
            padding: 8px;
            margin-top: 5px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }



        /* Button styling */
        #openModalButton {
            padding: 10px 20px;
            background-color: #18392B;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }

        #openModalButton:hover {
            background-color: #318392B3;
        }

        /* Modal container */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        /* Modal content box */
        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            max-height: 80%; /* Max height as a percentage of the viewport */
            overflow-y: auto; 
        }

        /* Close button styling */
        .close {
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            color: red;
        }

        .close:hover, .close:focus {
            color: #a42914;
        }




        /* Custom Save button styling */
        button[type="submit"] {
            width: 100%;
            padding: 12px 25px;
            background-color: #007bff; /* Blue background */
            color: white; /* White text */
            border: none;
            border-radius: 8px; /* Rounded corners */
            font-size: 16px; /* Medium font size */
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Shadow for depth */
            transition: all 0.3s ease;
        }

        /* Hover and active effects for Save button */
        button[type="submit"]:hover {
            background-color: #0056b3; /* Darker blue on hover */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15); /* Increased shadow */
            transform: translateY(-2px); /* Lift effect */
        }

        button[type="submit"]:active {
            transform: translateY(2px); /* Slight push-down effect */
            background-color: #004085; /* Even darker blue when clicked */
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
    @if(session('success_office'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Great...',
                text: @json(session('success_office')),
                confirmButtonText: 'OK'
            });
        </script>
    @endif
    

    @include('components.app-bar')
    <div>
    <h1 class="title1">Create Task</h1>
        <form method="POST" action="{{ route('admin.create') }}" class="main-content" enctype="multipart/form-data">
            @csrf
            @method('post')
            <label for="Name">Name:</label>
            <input type="text" name="task_name" required value="{{old('task_name')}}">
            <label for="filepath">Upload File (PDF):</label>
            <input type="file" name="filepath" accept=".pdf" required>
            <div id="form-container" class="form-container">
                <!-- Plus icon to add more forms -->
                <i class="plus-icon" id="add-form">+</i>
            </div>
            <button type="reset" class="btn-clear">Clear All</button>
            <button type="button" class="btn3" id="openModalButton" style="margin-left: 310px;margin-top: -36px;">Add Office</button>
            <button type="submit" class="btn4" style="margin-top: 5px;">CREATE</button>
        </form>
        <!--<button type="button" class="btn2" style="height:40px; padding:auto; margin-top:20px; margin-left:390px" onclick="window.history.back();">Go Back</button> -->
    </div>
    <div id="inputModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Enter Office Details</h2>
            <form id="inputForm" method="POST" action="{{ route('admin.addOffice') }}">
              @csrf
              <div class="form-group">
                <label for="inputField1">Office Name:</label>
                <input type="text" id="inputField1" name="office_name" required>
              </div>
              <button type="submit">Save</button>
            </form>
        </div>
    </div>
    


    <script>
        // Get modal and button elements
        var modal = document.getElementById("inputModal");
        var btn = document.getElementById("openModalButton");
        var closeBtn = document.querySelector(".close");

        // Show modal when button is clicked
        btn.onclick = function() {
            modal.style.display = "flex";
        }

        // Close modal when "x" is clicked
        closeBtn.onclick = function() {
            modal.style.display = "none";
        }

        // Close modal when user clicks outside the modal content
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

    <script>
        let formCount = 0; // Counter to give unique IDs to each form element
        let stepCount = 0;

        // Function to create a new form and add it to the container
        function createForm(officeName = '', task = '', time = '') {
            formCount++;
            stepCount++;

            const formContent = `
    <div class="form-content" id="form_${formCount}">
        <!-- X icon for removing the form -->
        <button class="close-icon" onclick="removeForm(${formCount})">&times;</button>

        <label style="font-size: 30px; font-weight:bold; margin-left:-10px;margin-bottom:20px">Step ${stepCount}</label>
        <label for="office_name_${formCount}">Name of the Office</label>
        <select name="office_name[]" id="office_name_${formCount}" required>
            @foreach($offices as $office)
                <option value="{{ $office->office_name }}" ${officeName === '{{ $office->office_name }}' ? 'selected' : ''}>
                    {{ $office->office_name }}
                </option>
            @endforeach
        </select>

        <label>Office Task</label>
        <input type="text" name="task[]" id="office_task_${formCount}" value="${task}" required>

        <label>Task Allotted Time</label>
        <input type="text" name="time[]" id="task_time_${formCount}" value="${time}" required>
    </div>
            `;

            // Create a new div and set its inner HTML to the new form content
            const newFormDiv = document.createElement('div');
            newFormDiv.innerHTML = formContent;

            // Append the new form to the form container
            document.getElementById('form-container').appendChild(newFormDiv);

            // Remove the plus icon from the last form and re-add it after the new form
            const lastPlusIcon = document.querySelector('.plus-icon');
            if (lastPlusIcon) lastPlusIcon.remove();

            const newPlusIcon = document.createElement('i');
            newPlusIcon.classList.add('plus-icon');
            newPlusIcon.textContent = '+';
            newPlusIcon.addEventListener('click', createForm);
            document.getElementById('form-container').appendChild(newPlusIcon);
        }

        // Function to remove a form
        function removeForm(formId) {
            const formToRemove = document.getElementById(`form_${formId}`);
            if (formToRemove) formToRemove.remove();
            stepCount--; // Decrement step count

            // Update step numbers for remaining forms
            const remainingForms = document.querySelectorAll('.form-content');
            remainingForms.forEach((form, index) => {
                const stepHeader = form.querySelector('h3');
                if (stepHeader) stepHeader.textContent = `Step ${index + 1}`;
            });
        }

        // Validate form
        function validateForm() {
            const formContents = document.querySelectorAll('.form-content');
            if (formContents.length === 0) {
                Swal.fire({ icon: 'error', title: 'Validation Error', text: 'You must add at least one office task.', confirmButtonText: 'OK' });
                return false;
            }

            let isValid = false;
            formContents.forEach((form) => {
                const officeName = form.querySelector('select[name="office_name[]"]');
                const task = form.querySelector('input[name="task[]"]');
                const time = form.querySelector('input[name="time[]"]');

                if (officeName && officeName.value && task && task.value && time && time.value) {
                    isValid = true;
                }
            });

            if (!isValid) {
                Swal.fire({ icon: 'error', title: 'Validation Error', text: 'You must fill at least one set of office task information.', confirmButtonText: 'OK' });
                return false;
            }

            return true;
        }

        // Add the initial event listener to the first plus icon
        document.getElementById('add-form').addEventListener('click', createForm);

        // Re-populate the old values when the page loads
        document.addEventListener('DOMContentLoaded', function () {
            const oldOfficeNames = @json(old('office_name', []));
            const oldTasks = @json(old('task', []));
            const oldTimes = @json(old('time', []));

            oldOfficeNames.forEach((officeName, index) => {
                const task = oldTasks[index] || '';
                const time = oldTimes[index] || '';
                createForm(officeName, task, time);
            });

            
        });

        // Attach validation to the form submit event
        document.querySelector('form').addEventListener('submit', function (event) {
            if (!validateForm()) event.preventDefault();
        });
    </script>

</body>
</html>
