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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creating Holiday</title>
    
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
                    window.location.href = '{{ route("admin.listOfTaskPage") }}'; // Replace with your actual dashboard route
                }
            });
        </script>
    @endif
    @if(session('success_office'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Great...',
                text: @json(session('success_office')),
                confirmButtonText: 'OK'
            });
        </script>
    @endif
    

    @include('components.app-bar', ['admin' => $admin])
    <div>
    <h1 class="title3">Create Holiday</h1>
        <form method="POST" action="{{ route('admin.holidays') }}">
            @csrf
            @method('post')
            <label for="Name">Description:</label>
            <input type="text" name="desc" required value="{{old('desc')}}">
            <label for="date">School Holiday Date</label>
            <input type="date" name="date" required>
            <button type="submit" class="btn4" style="margin-top: 5px;">save</button>
        </form>
        <!--<button type="button" class="btn2" style="height:40px; padding:auto; margin-top:20px; margin-left:390px" onclick="window.history.back();">Go Back</button> -->
    </div>

    @foreach ($holidays as $holiday)
            {{ $holiday->description }}
            {{ $holiday->holiday_date }}
    @endforeach




<script>
    // Function to refresh the stats every 5 seconds
    function refreshStats() {
        $.ajax({
            url: '/audit', // The route where you fetch updated stats
            method: 'GET',
            success: function(response) {
                // The data is fetched but not used for updating the page.
                console.log(response); // Optional: Log the data to the console for debugging
            }
        });
    }

    // Refresh the stats every 5 seconds (5000 milliseconds)
    setInterval(refreshStats, 30000);
</script>
</body>
</html>
