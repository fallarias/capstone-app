<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/loginstyle.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Login</title>
</head>
<body>
    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Great...',
                text: @json(session('error')),
                confirmButtonText: 'OK'
            })
        </script>
    @endif
    <div class="container">
        <div class="row">
            <div class="column">
                <div class="image-container">
                    <img src="{{ asset('img/isu.png') }}" alt="First Image" class="image">
                    <img src="{{ asset('img/ict.png') }}" alt="Second Image" class="image1" style="top:9px">
                    <p class="bottom-text">ISUE CANNER</p>
                    <p class="bottom-text1">
                        <span style="font-size: 28px;">D</span>ocument 
                        <span style="font-size: 28px;">T</span>racking and 
                        <span style="font-size: 28px;">M</span>onitoring  
                        <span style="font-size: 28px;">S</span>ystem using 
                        <span style="font-size: 28px;margin-left:5px">QR</span> 
                        <span style="font-size: 28px;margin-left:5px">C</span>ode.
                    </p>
                </div>
            </div>

            <div id="loading-screen" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 9999; text-align: center; color: white; font-size: 24px;">
                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                    <i class="fas fa-spinner fa-spin" style="font-size: 50px;"></i>
                    <p>Loading...</p>
                </div>
            </div>

            <div class="column1">
                <h1 style="font-size: 45px; font-weight:bold; margin-left: 110px; margin-top: 30px; margin-bottom: -20px; color:forestgreen"> WELCOME </h1>
                <h2 style="font-weight: 10; color:black; font-size:20px;  margin-left: 110px; color:darkgrey; margin-bottom:-60px;">Sign in to continue</h2>
                <form method="post" action="{{ route('admin.logins') }}" >
                    @csrf
                    @method('post')
                    <div class="field input" style="position: relative;">
                        <label for="email" style="color:forestgreen; font-size:22px">Email</label>
                        <input type="text" name="email" id="email" autocomplete="off" required placeholder="Enter your email" required value="{{ old('email') }}" style="padding-right: 30px;">
                        <i class="fas fa-envelope" style="position: absolute; right: 10px; top: 50px; color: forestgreen; font-size:22px"></i>
                    </div>

                    <div class="field input" style="position: relative;"> 
                        <label for="password" style="margin-bottom: -10px; margin-top: 25px;color:forestgreen;font-size:22px">Password</label><br>
                        <input type="password" id="password" name="password" required placeholder="Enter your password" style="padding-right: 30px;">
                        <i class="fas fa-eye" id="togglePassword" style="position: absolute; right: 10px; top: 50px; cursor: pointer; color: forestgreen;font-size:22px"></i>
                        <br>
                        <input type="checkbox" id="showPassword" style="margin-top: 20px;cursor:pointer"><span style="color: forestgreen;">Show Password</span>
                    </div>

                    <div class="field">
                        <button type="submit" class="gradient-btn">Login</button>
                    </div>
                </form>

                <div style="text-align: center; margin-top: -110px;">
                    <p style="color: darkgrey;">Don't have an account? <a href="{{ route('client.registrations') }}" style="color: forestgreen; text-decoration: none;">Register here</a></p>
                </div>
                
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle password visibility toggle
            document.getElementById('showPassword').addEventListener('change', function() {
                var passwordField = document.getElementById('password');
                if (this.checked) {
                    passwordField.type = 'text';
                } else {
                    passwordField.type = 'password';
                }
            });

            // Display SweetAlert2 error if there are any
            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '{{ $errors->first() }}',
                    confirmButtonText: 'OK'
                });
            @endif
        });
    </script>


<script>
document.getElementById('togglePassword').addEventListener('click', function (e) {
    const password = document.getElementById('password');
    if (password.type === 'password') {
        password.type = 'text';
        this.classList.remove('fa-eye');
        this.classList.add('fa-eye-slash');
    } else {
        password.type = 'password';
        this.classList.remove('fa-eye-slash');
        this.classList.add('fa-eye');
    }
});

</script>

<script>
function showLoadingScreen(event) {
    event.preventDefault(); // Prevent the default form submission

    // Show the loading screen
    document.getElementById('loading-screen').style.display = 'block';

    // Disable form inputs to prevent multiple submissions
    document.querySelector('form').querySelectorAll('input, button').forEach(element => {
        element.disabled = true;
    });

    // Wait for 5 seconds before submitting the form
    setTimeout(function() {
        document.getElementById('loginForm').submit(); // Submit the form after 5 seconds
    }, 5000);
}


</script>
<script>
    // Function to refresh the stats every 5 seconds
    function refreshStats() {
        $.ajax({
            url: '/audit_login', // The route where you fetch updated stats
            method: 'GET',
            success: function(response) {
                // The data is fetched but not used for updating the page.
                console.log(response); // Optional: Log the data to the console for debugging
            }
        });
    }

    // Refresh the stats every 5 seconds (5000 milliseconds)
    setInterval(refreshStats, 100000);
</script>
</body>
</html>




