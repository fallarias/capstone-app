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
    <link rel="stylesheet" href="{{ asset('css/createTask.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creating New Task</title>
    
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
                    window.location.href = '{{ route("admin.listOfTaskPage") }}'; // Replace with your actual dashboard route
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
        <select name="time[]" id="task_time_${formCount}" required>
            @for ($i = 1; $i <= 100; $i++)
                <option value="{{ $i }}">{{ $i }} hour{{ $i !== 1 ? 's' : '' }}</option>
            @endfor
        </select>
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
                const time = form.querySelector('select[name="time[]"]');

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
