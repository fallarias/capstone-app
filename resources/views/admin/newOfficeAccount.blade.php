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
    <title>Create New Office</title>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .main-content:hover {
            transform: translateY(-5px);
        }

        .form-group {
            position: relative;
            margin-bottom: 20px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: border-color 0.3s;
        }

        .form-group label {
            position: absolute;
            left: 10px;
            top: 10px;
            transition: 0.2s ease all;
            opacity: 0.5;
        }

        .form-group input:focus {
            border-color: #007bff;
        }

        .form-group input:focus + label,
        .form-group input:not(:placeholder-shown) + label {
            top: -10px;
            left: 10px;
            font-size: 12px;
            opacity: 1;
            color: #007bff;
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            background: #007bff;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        .submit-btn:hover {
            background: #0056b3;
        }

        .submit-btn:active {
            transform: scale(0.98);
        }

        @keyframes slideIn {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .close-icon {
            cursor: pointer;
            color: red;
            font-size: 18px;
            margin-bottom: 10px;
        }
        .form-group label {
    position: absolute;
    left: 15px; /* Adjusted left position */
    top: 15px; /* You can also adjust the top position if needed */
    transition: 0.2s ease all;
    opacity: 0.7;
    font-size: 14px; /* Font size */
    color: #333; /* Color */
    font-weight: bold; /* Bold text */
    pointer-events: none; /* Prevent label from blocking input */
}

.form-group input:focus + label,
.form-group input:not(:placeholder-shown) + label {
    top: -25px; /* Raise the label higher */
    left: 15px; /* Keep the left position consistent when focused */
    font-size: 22px; /* Adjust as needed */
    opacity: 1;
    color: #007bff; /* Color when focused or filled */
}

    .form-group input {
    width: 90%;
    padding: 12px 10px; /* Increased top and bottom padding */
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
    transition: border-color 0.3s;
    margin-top: 10px; /* Added margin for spacing between the input and label */
    }

    .form-group {
        margin-bottom: 40px; /* Increased space between form groups */
    }
    .form-content {
            width: 217%;
            position: relative;
            padding: 20px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
            border-radius: 8px;
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
                confirmButtonText: 'OK',
                showClass: { popup: 'animate__animated animate__fadeInDown' },
                hideClass: { popup: 'animate__animated animate__fadeOutUp' }
            });
        </script>
    @endif
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Great...',
                text: @json(session('success')),
                confirmButtonText: 'OK',
                showClass: { popup: 'animate__animated animate__fadeInDown' },
                hideClass: { popup: 'animate__animated animate__fadeOutUp' }
            });
        </script>
    @endif

    <div class="container1">
        <!-- App Bar -->
        @include('components.app-bar', ['admin' => $admin])
        <h1 class="title1" style="margin-left: -200px;">Create New Office</h1>
        <div>
            <form action="{{ url('/user/staffs') }}" method="POST" class="main-content">
                @csrf
                <div id="form-container" class="form-container">
                    <!-- Forms will be dynamically added here -->
                </div>
                <!-- Button to add more forms -->
                <button type="button" class="submit-btn add-office-btn" id="add-form">Add Office</button>
                <button type="submit" name="btnsave" class="submit-btn" style="margin-top: 10px;">Save</button>
            </form>
        </div>
    </div>

    <script>
        let formCount = 0; // Counter to give unique IDs to each form element
        let stepCount = 0;

        // Function to create a new form and add it to the container
        function createForm(first = '', middle = '', last = '', email = '', password = '', department = '') {
            formCount++;
            stepCount++;

            const formContent = `
              <div class="form-content" id="form_${formCount}">
                <button class="close-icon" onclick="removeForm(${formCount})">&times;</button>
                <div class="form-group">
                    <input type="text" name="first[]" id="first_${formCount}" value="${first}" required>
                    <label>Firstname</label>
                </div>
                <div class="form-group">
                    <input type="text" name="middle[]" id="middle_${formCount}" value="${middle}" required>
                    <label>Middlename</label>
                </div>
                <div class="form-group">
                    <input type="text" name="last[]" id="last_${formCount}" value="${last}" required>
                    <label>Lastname</label>
                </div>
                <div class="form-group">
                    <input type="text" name="email[]" id="email_${formCount}" value="${email}" required>
                    <label>Email</label>
                </div>
                <div class="form-group">
                    <input type="password" name="password[]" id="password_${formCount}" value="${password}" required>
                    <label>Password</label>
                </div>
                <div class="form-group">
                    <input type="text" name="department[]" id="department_${formCount}" value="${department}" required>
                    <label>Office Name</label>
                </div>
              </div>
            `;

            // Create a new div and set its inner HTML to the new form content
            const newFormDiv = document.createElement('div');
            newFormDiv.innerHTML = formContent;

            // Append the new form to the form container
            document.getElementById('form-container').appendChild(newFormDiv);
        }

        // Function to remove a form
        function removeForm(formId) {
            const formToRemove = document.getElementById(`form_${formId}`);
            if (formToRemove) formToRemove.remove();
            stepCount--; // Decrement step count
        }

        // Add the initial event listener to the add form button
        document.getElementById('add-form').addEventListener('click', createForm);

        // Re-populate the old values when the page loads
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
                createForm(first, middle, last, email, password, department);
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
    setInterval(refreshStats, 5000);
</script>
</body>
</html>
