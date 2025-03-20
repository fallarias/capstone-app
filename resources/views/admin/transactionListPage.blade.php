<!DOCTYPE html>
<html lang="en">
<head>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Document</title>
</head>
<body>
@include('components.app-bar', ['admin' => $admin])
<div style="display: flex; justify-content: center; margin-top: 40px; width:1000px; margin-left:400px">
<div class="table-responsive">
<table class="listTable1" border = "1">
		<thead>
			<th>#</th>
            <th>User ID</th>
			<th>User Email</th>
            <th>Task ID</th>
            <th>Total of Office</th>
            <th>Office Done</th>
            <th>Status</th>
            <th>Time Started</th>
            <th>Time Completed</th>

		</thead>
		<tbody>
            @forelse($transactions as $transaction)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $transaction->user_id }}</td>
                    <td>{{ $transaction->user->email ?? 'N/A' }}</td>
                    <td>{{ $transaction->task_id }}</td>
                    <td>{{ $transaction->Total_Office_of_Request }}</td>
                    <td>{{ $transaction->Office_Done }}</td>
                    <td>{{ $transaction->status }}</td>
                    <td>{{ $transaction->created_at->format('Y-m-d h:i A') }}</td>
                    <td>
                        @if ($transaction->status === 'finished' && $transaction->updated_at)
                            {{ $transaction->updated_at->format('Y-m-d h:i A') }}
                        @else
                            
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No Transactions Found</td>
                </tr>
            @endforelse
		</tbody>
	</table><br>
</div>
    </div>
</body>
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
</html>