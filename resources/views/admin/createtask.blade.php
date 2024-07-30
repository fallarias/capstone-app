<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creating New Task</title>
</head>
<body>
<form method="POST" action="{{route('admin.create')}}">
        @csrf
        @method('post')
        <label>Name of the Office</label>
        <input type="text" name="office_name">
        <label>Office Task</label>
        <input type="text" name="task">
        <label>Task Alloted Time</label>
        <input type="text" name="time">
        <button type="submit" name="save">Create</button>
    </form>
</body>
</html>