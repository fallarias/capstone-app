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
            margin-bottom: 10px;
            width: fit-content;
            padding: 20px;
            border: 1px solid #ccc;
        }

        .plus-icon {
            font-size: 24px;
            cursor: pointer;
            display: block;
            margin-top: 20px;
            margin-right: 700px;
        }
        input, select{
            width: 13%;
            height: 100%;
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
            });
        </script>
    @endif
    

    @include('components.app-bar')
    <div>
        <form method="POST" action="{{ route('admin.create') }}" class="main-content">
            <h1 class="title1">Create Task</h1>
            @csrf
            @method('post')

            <label for="Name">Name:</label>
            <input type="text" placeholder="PR" name="task_name">

            <!-- Container for dynamic form content -->
            <div id="form-container" class="form-container">
                
                    <!-- Plus icon at the bottom of the form -->
                    <i class="plus-icon" id="add-form">+</i>

            </div>
                <button type="reset" class="btn-clear">Clear All</button>
                <button type="submit" class="btn3">Proceed</button>
            </div>
            
        </form>
        <button type="button" class="btn2" onclick="window.history.back();">Go Back</button>
    </div>

    <script>
        let formCount = 0; // Counter to give unique IDs to each form element
        let stepCount = 0;
        // Function to create a new form and add it to the container
        function createForm() {
            formCount++; // Increment the form counter
            stepCount++;
            // Define the new form content with unique IDs and name[]
            const formContent = `
            <h3>Step ${stepCount}</h3>
                <div class="form-content">
                    <label for="office_name_${formCount}">Name of the Office</label>
                    <select name="office_name[]" id="office_name_${formCount}" >
                        <option value="" disabled selected></option>
                        <option value="EO Office">EO Office</option>
                        <option value="Procurement Office">Procurement Office</option>
                        <option value="Budget Office">Budget Office</option>
                        <option value="Accounting Office">Accounting Office</option>
                        <option value="Supplies Office">Supplies Office</option>
                    </select>
                    <label>Office Task</label>
                    <input type="text" name="task[]" id="office_task_${formCount}">

                    <label>Task Alloted Time</label>
                    <input type="text" name="time[]"  id="task_time_${formCount}">

                    <!-- Plus icon at the bottom of the form -->
                    <i class="plus-icon" id="add-form">+</i>
                    
                </div>
                
            `;

            // Remove the current plus icon from the last form
            const lastPlusIcon = document.querySelector('.plus-icon');
            if (lastPlusIcon) {
                lastPlusIcon.remove();
            }

            // Create a new div and set its inner HTML to the new form content
            const newFormDiv = document.createElement('div');
            newFormDiv.innerHTML = formContent;

            // Append the new form to the form container
            document.getElementById('form-container').appendChild(newFormDiv);

            // Add the event listener to the new plus icon
            const newPlusIcon = newFormDiv.querySelector('.plus-icon');
            newPlusIcon.addEventListener('click', createForm);
        }

        // Add the initial event listener to the first plus icon
        document.getElementById('add-form').addEventListener('click', createForm);
    </script>
</body>
</html>
