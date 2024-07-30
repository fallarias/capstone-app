<!-- resources/views/example.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Form Example</title>
</head>
<body>
    <h1>Create Admin Form</h1>

    <form method="POST" action="{{route('admin.signup')}}">
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
        <label for="account_type">Account Type</label>
            <select name="account_type" id="account_type" required>
                <option value="">Select a role</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
                <option value="supplier">Supplier</option>
            </select>
        </div>
        <div>
            <button type="submit">Submit</button>
        </div>
    </form>
</body>
</html>
