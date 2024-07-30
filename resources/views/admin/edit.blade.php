<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
        @if ($errors->any())
                <div>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        <form action='{{route('admin.update', $data->create_id)}}' method="POST">
                @csrf
                <label for="office_name">Office Name:</label>
                <input type="text" id="office_name" name="Office_name" value="{{ $data->Office_name }}">

                <label for="Office_task">Office Task:</label>
                <input type="text" id="Office_task" name="Office_task" value="{{ $data->Office_task }}">

                <label for="New_alloted_time">Allotted Time:</label>
                <input type="text" id="New_alloted_time" name="New_alloted_time" value="{{ $data->New_alloted_time }}">
                <!-- Add other fields as needed -->
                <button type="submit">Update</button>
            </form>

</body>
</html>