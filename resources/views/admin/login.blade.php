<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>login</h1>
<form method="post" action="{{route('admin.logins')}}">
        @csrf
        @method('post')
        <div>
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" required>
        </div>
        <div>
            <label for="password">password:</label>
            <input type="text" id="password" name="password" required>
        </div>
        <div>
            <button type="submit">Submit</button>
        </div>
    </form>
    <a href="{{url('/signup')}}">Create Account</a>
</body>
</html>