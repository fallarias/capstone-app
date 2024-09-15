
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
</head>
<body>
@include('components.app-bar')
    <div>
        <form method="POST" action="{{route('admin.create')}}" class="main-content">
        <h1 class="title1">Create Task</h1>

            @csrf
            @method('post')
            <label for="office_name">Name of the Office</label>
            <select name="office_name" id="office_name">
                <option value="" disabled selected></option> <!-- Default "None" option -->
                <option value="office1">Office 1</option>
                <option value="office2">Office 2</option>
                <option value="office3">Office 3</option>
                <option value="office4">Office 4</option>
                <!-- Add more options as needed -->
            </select>


            <label>Office Task</label>
            <select name="task" id="office_task">
                <option value="" disabled selected></option> <!-- Default "None" option -->
                <option value="office1">Office 1</option>
                <option value="office2">Office 2</option>
                <option value="office3">Office 3</option>
                <option value="office4">Office 4</option>
                <!-- Add more options as needed -->
            </select>
            <label>Task Alloted Time</label>
            <select name="time" id="task_time">
                <option value="" disabled selected></option> <!-- Default "None" option -->
                <option value="office1">Office 1</option>
                <option value="office2">Office 2</option>
                <option value="office3">Office 3</option>
                <option value="office4">Office 4</option>
                <!-- Add more options as needed -->
            </select>
            <button type="reset" class="btn-clear">Clear All</button>
            <button type="submit" class="btn3">Proceed</button>

        </form>
        <button type="button" class="btn2" onclick="window.history.back();">Go Back</button>
     </div>
</body>
</html>