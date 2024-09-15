<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>email</h2>
    <p>{{ $user->email }}</p>
    <h2>password</h2>
    <p>{{ $user->password }}</p>
    <a href="{{url('/dashboard')}}">Back</a>
    
</body>
</html>