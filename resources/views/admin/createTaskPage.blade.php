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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    
<div class="container">
    @include('components.app-bar', ['admin' => $admin])

<div class="task-container">
        <div class="form-section">
            <h1 class="title3">Create Task</h1>
                <form method="POST" action="{{ route('admin.create') }}" class="main-content" enctype="multipart/form-data">
                @csrf
                @method('post')
                <label for="Name">Name:</label>
                <input type="text" name="task_name" required value="{{old('task_name')}}">
                <label for="filepath">Upload File (PDF):</label>
                <input type="file" name="filepath" accept=".doc,.docx,.xlsx,.xls" required class="file">
                <div id="form-container" >
                    <!-- Plus icon to add more forms -->
                    <i class="plus-icon" id="add-form">+</i>
                </div>
                <button type="reset" class="btn-clear">Clear All</button>
                <button class="btn3 addUser" id="openModalButton" >Add User</button>
                <button class="btn4" style="margin-top: 5px;">CREATE</button>
            </form>
            <!--<button type="button" class="btn2" style="height:40px; padding:auto; margin-top:20px; margin-left:390px" onclick="window.history.back();">Go Back</button> -->
        </div>

            <div class="recent-tasks">
            <h3 class="recentTask-text">Recent Tasks</h3>
                    <div class="recent-task-container loading">
                @forelse($name as $counter)    
                    <p class="task-item loading-item">{{ $counter->name }}</p>
                @empty
                    <p>No recent task</p>
                @endforelse
                    </div>
            </div>
    </div>





<div id="inputModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h1 class="title1" style="margin-left: -10px; font-size:28px">Create New Office</h1>
        <form id="inputForm" method="POST" action="{{ route('admin.newOfficeAccounts') }}">
        @csrf
            <div id="modal-form-container" class="form-container">
                <!-- Forms will be dynamically added here -->
            </div>
            <!-- Button to add more forms -->
            <button type="button" class="submit-btn" style="background-color: #005733;" id="modal-add-form">Add User</button>
            <button type="submit" name="btnsave" class="submit-btn" style="margin-top: 10px; background-color: #005733;">Save</button>
        </form>

        </div>
        </div>

        </div>

    

    
    <script>
    let formCounts = 0; // Counter to give unique IDs to each form element
    let stepCounts = 0;

    // Function to create a new form and add it to the container
    function createForms(first = '', middle = '', last = '', email = '', password = '', department = '') {
        formCounts++;
        stepCounts++;

        const formContents = `
          <div class="form-content" id="form_${formCounts}">
            <button class="close-icon" onclick="removeForm(${formCounts})">&times;</button>
            <div class="form-group">
                <input type="text" name="first[]" id="first_${formCounts}" value="${first}" required>
                <label class="labelForm2">Firstname</label>
            </div>
            <div class="form-group">
                <input type="text" name="middle[]" id="middle_${formCounts}" value="${middle}" required>
                <label class="labelForm2">Middlename</label>
            </div>
            <div class="form-group">
                <input type="text" name="last[]" id="last_${formCounts}" value="${last}" required>
                <label class="labelForm2">Lastname</label>
            </div>
            <div class="form-group">
                <input type="text" name="email[]" id="email_${formCounts}" value="${email}" required>
                <label class="labelForm2">Email</label>
            </div>
            <div class="form-group">
                <input type="password" name="password[]" id="password_${formCounts}" value="${password}" required>
                <label class="labelForm2">Password</label>
            </div>
            <div class="form-group">
                <input type="text" name="department[]" id="department_${formCounts}" value="${department}" required>
                <label class="labelForm2">Office Name</label>
            </div>
          </div>
        `;

        // Create a new div and set its inner HTML to the new form content
        const newFormDiv = document.createElement('div');
        newFormDiv.innerHTML = formContents;

        // Append the new form to the modal's form container
        document.getElementById('modal-form-container').appendChild(newFormDiv);
    }

    // Function to remove a form
    function removeForm(formId) {
        const formToRemove = document.getElementById(`form_${formId}`);
        if (formToRemove) formToRemove.remove();
        stepCounts--; // Decrement step count
    }

    // Add the event listener to the modal's add form button
    document.getElementById('modal-add-form').addEventListener('click', function () {
        createForms(); // Call createForm without passing any arguments
    });
    


    // Re-populate old values when the page loads
    document.addEventListener('DOMContentLoaded', function () {
        const firsts = @json(old('first', []));
        const middles = @json(old('middle', []));
        const lasts = @json(old('last', []));
        const emails = @json(old('email', []));
        const passwords = @json(old('password', []));
        const departments = @json(old('department', []));
        
        firsts.forEach((first, index) => {
            const middle = middles[index] || '';
            const last = lasts[index] || '';
            const email = emails[index] || '';
            const password = passwords[index] || '';
            const department = departments[index] || '';
            createForms(first, middle, last, email, password, department);
        });
    });
</script>

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
        let formCount = 1; // Counter to give unique IDs to each form element
        let stepCount = 0;

        // Function to create a new form and add it to the container
        function createForm(officeName = '', task = '', time = '') {
            formCount++;
            stepCount++;

            const formContent = `
    <div class="form-content1" id="form_${formCount}">
        <!-- X icon for removing the form -->
        <button class="close-icon" onclick="removeForm(${formCount})">&times;</button>

        <label class="design-step">Step ${stepCount}</label>
        <label for="office_name_${formCount}">Name of the Office</label>
        <select name="office_name[]" id="office_name_${formCount}" required>
            @foreach($offices as $office)
                <option value="{{ $office->department }}" ${officeName === '{{ $office->department }}' ? 'selected' : ''}>
                    {{ $office->department }}
                </option>
            @endforeach
        </select>

        <label>Office Task</label>
        <input  class="officeTask" name="task[]" id="office_task_${formCount}" value="${task}" required>

        <label>Task Allotted Time</label>
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
            const formContents = document.querySelectorAll('.form-content1');
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
