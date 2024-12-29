<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/registerstyle.css') }}">
    <title>Register</title>
    <style>
        .form-section {
            display: none; /* Hide all sections initially */
        }
        .form-section.active {
            display: block; /* Show active section */
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
                    window.location.href = '{{ url("login") }}'; // Replace with your actual dashboard route
                }
            });
        </script>
    @endif
    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error...',
                text: @json(session('error')),
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    <div class="container">
        <div class="row">
            <div class="column">
                <div class="image-container">
                    <img src="{{ asset('img/isu.png') }}" alt="First Image" class="image">
                    <img src="{{ asset('img/ict.png') }}" alt="Second Image" class="image1" style="top:9px">
                    <p class="bottom-text">ISU-CANNER</p>
                    <p class="bottom-text1">
                        <span style="font-size: 28px;">D</span>ocument 
                        <span style="font-size: 28px;">T</span>racking and 
                        <span style="font-size: 28px;">M</span>onitoring  
                        <span style="font-size: 28px;">S</span>ystem using 
                        <span style="font-size: 28px;margin-left:5px">QR</span> 
                        <span style="font-size: 28px;margin-left:5px">C</span> 
                        <span style="font-size: 28px;margin-left:-5px">ode.</span>
                    </p>
                </div>
            </div>
            <div class="column1">
            <h1 style="font-size: 45px; font-weight:bold; margin-left: 70px; margin-top: -10px; margin-bottom: -20px; color:forestgreen"> CREATE ACCOUNT </h1>
            <div class="img-container">
                <img id="formImage" src="https://th.bing.com/th/id/OIP.z3EGpAMJvI4OCDI-caSNLgHaHa?w=1200&h=1200&rs=1&pid=ImgDetMain" alt="Profile" class="img-person">
                <div id="formText" style="text-align: center; margin-bottom: -60px; margin-left: 15px; margin-top: 15px;">
                    <p style="color: darkgrey;">Already have an account? <a href="{{ url('/login') }}" style="color: forestgreen; text-decoration: none;">Login here</a></p>
                </div>
            </div>

                <form id="registrationForm" action="{{ route('client.registrations') }}" method="POST">
                    @csrf
                    
                    <!-- First Section -->
                    <div class="form-section active" id="section1" style="margin-top: -30px;">
                        <div class="field input">
                            <label for="firstname"style="color:forestgreen; font-size:22px">First Name:</label>
                            <input type="text" id="firstname" name="firstname" required>
                        </div>
                        <div class="field input">
                            <label for="lastname" style="color:forestgreen; font-size:22px">Last Name:</label>
                            <input type="text" id="lastname" name="lastname" required>
                        </div>
                        <div class="field input">
                            <label for="middlename" style="color:forestgreen; font-size:22px">Middle Name:</label>
                            <input type="text" id="middlename" name="middlename" required>
                        </div>
                        <button type="button" class="styled-button" onclick="nextSection(1)">Next</button>
                    </div>

                    <!-- Second Section -->
                    <div class="form-section" id="section2">
                        <div class="field input">
                            <label for="department" style="color:forestgreen; font-size:22px">Department:</label>
                            <input type="text" id="department" name="department" required>
                        </div>
                        <div class="field input">
                            <label for="email" style="color:forestgreen; font-size:22px">Email:</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="button-container">
                            <button type="button" class="styled-button" onclick="backSection(2)">Back</button>
                            <button type="button" class="styled-button2" onclick="nextSection(2)">Next</button>
                        </div>

                    </div>

                    <!-- Third Section -->
                    <div class="form-section" id="section3">
                        <div class="field input">
                            <label for="password" style="color:forestgreen; font-size:22px">Password:</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        <div class="field input">
                            <label for="password_confirmation" style="color:forestgreen; font-size:22px">Confirm Password:</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required>
                        </div>
                        <div class="button-container">
                            <button type="button" class="styled-button" onclick="backSection(3)">Back</button> <!-- Updated this line -->
                            <button type="submit" class="styled-button2">Save</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
function updateFormContent(section) {
    const imageElement = document.getElementById('formImage');
    const textElement = document.getElementById('formText');

    switch(section) {
        case 1:
            imageElement.src = "https://th.bing.com/th/id/OIP.z3EGpAMJvI4OCDI-caSNLgHaHa?w=1200&h=1200&rs=1&pid=ImgDetMain";
            textElement.innerHTML = `<p style="color: darkgrey;">Already have an account? <a href="{{ url('/login') }}" style="color: forestgreen; text-decoration: none;">Login here</a></p>`;
            break;
        case 2:
            imageElement.src = "https://th.bing.com/th/id/OIP.3nWmjUyww_WxePikG4CxnAHaHa?rs=1&pid=ImgDetMain";
            textElement.innerHTML = `<p style="color: darkgrey; font-size:20px">Join with your email address</p>`;
            break;
        case 3:
            imageElement.src = "https://cdn-icons-png.flaticon.com/512/7080/7080679.png";
            textElement.innerHTML = `<p style="color: darkgrey;">Finally, set your password!</p>`;
            break;
    }
}

function nextSection(current) {
    // Check required fields in the current section
    const currentSection = document.getElementById('section' + current);
    const inputs = currentSection.querySelectorAll('input[required]');
    let allFilled = true;

    inputs.forEach(input => {
        if (input.value.trim() === '') {
            allFilled = false; // Mark as not filled if any required input is empty
        }
    });

    if (!allFilled) {
        // Show warning message if not all required fields are filled
        Swal.fire({
            icon: 'warning',
            title: 'Incomplete Form',
            text: 'You must fill up all required fields before proceeding to the next form.',
            confirmButtonText: 'OK'
        });
        return; // Exit the function if fields are missing
    }

    // Update the form content based on the next section
    updateFormContent(current + 1);

    // Hide current section
    currentSection.classList.remove('active');

    // Show next section
    document.getElementById('section' + (current + 1)).classList.add('active');
}

function backSection(current) {
    // Hide current section
    document.getElementById('section' + current).classList.remove('active');

    // Show previous section
    const previous = current - 1;
    document.getElementById('section' + previous).classList.add('active');

    // Update the form content based on the previous section
    updateFormContent(previous);
}
</script>

</body>
</html>
