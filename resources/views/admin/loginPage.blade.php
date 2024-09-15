<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/loginstyle.css') }}">
    <title>Login</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="column">
                <div class="image-container">
                    <img src="{{ asset('img/isu.png') }}" alt="First Image" class="image">
                    <img src="{{ asset('img/ict.png') }}" alt="Second Image" class="image1" style="top:9px">
                    <p class="bottom-text">ISUE CANNER</p>
                    <p class="bottom-text1">
                        <span style="font-size: 28px;">C</span>ontrol of 
                        <span style="font-size: 28px;">A</span>rchival and 
                        <span style="font-size: 28px;">N</span>otification of 
                        <span style="font-size: 28px;">N</span>etwork 
                        <span style="font-size: 28px;">E</span>vents 
                        <span style="font-size: 28px;">R</span>ecords.
                    </p>
                </div>
            </div>
            <div class="column1">
                <p style="font-size: 25px; font-weight:bold; margin-left: -30px; margin-top: 10px; margin-bottom: 50px; color:forestgreen"> Login to your <span style="font-weight: 10; color:black">Account</span></p>
                <form method="post" action="{{ route('admin.logins') }}">
                    @csrf
                    @method('post')
                    <div class="field input">
                        <label for="email" style="color:forestgreen">Email</label>
                        <input type="text" name="email" id="email" autocomplete="off" required value="{{ old('email') }}">
                    </div>
                    <div class="field input"> 
                        <label for="password" style="margin-bottom: -10px; margin-top: 25px;color:forestgreen">Password</label><br>
                        <input type="password" id="password" name="password" required placeholder="Enter your password"><br>
                        <input type="checkbox" id="showPassword" style="margin-top: 20px;"><span style="color: forestgreen;">Show Password</span>
                    </div>
                    <div class="field">
                        <button type="submit" class="btn">Login</button>
                    </div>
                </form>
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
</body>
</html>



