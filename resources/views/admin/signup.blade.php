

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Layout</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/loginstyle.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<div class="container">
    <div class="row">   
        <div class="column">
            <div class="image-container">
                <img src="img/isu.png" alt="First Image" class="image">
                <img src="img/ict.png" alt="Second Image" class="image1" style="top:9px">
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
            <p style="font-size: 25px; font-weight:bold; margin-left: -190px; margin-top: 10px; margin-bottom: 70px"> Sign up <span style="font-weight: 10;">Now</span></p>
            <form method="POST" action="{{route('admin.signup')}}">            
            @csrf
        @method('post')
        <div class="field input">
            <label for="email" style="margin-bottom: -10px">Email</label><br>
            <input type="text" id="email" name="email" required>
        </div>
        <div class="field input"> 
            <label for="password" style="margin-bottom: -10px;margin-top: 20px">password</label><br>
            <input type="text" id="password" name="password" required>
        </div>

        <div class="field">
        <label for="account_type" style="margin-top: 20px">Account Type</label>
        <select name="account_type" id="account_type" required class="accType">
            <option value="">Select a role</option>
            <option value="admin">Admin</option>
            <option value="user">User</option>
            <option value="supplier">Supplier</option>
        </select>
        </div>

        <div>
            <button type="submit" class="btn">Submit</button>
        </div>
                <p>Have already account? <a href="{{url('/login')}}" style="color: #0f965e;">Login here</a></p>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('showPassword').addEventListener('change', function() {
            var passwordField = document.getElementById('passcode');
            if (passwordField) {
                if (this.checked) {
                    passwordField.type = 'text';
                } else {
                    passwordField.type = 'password';
                }
            } else {
                console.error('Password field not found');
            }
        });
    });
</script>


</body>
</html>
