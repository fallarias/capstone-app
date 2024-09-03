<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    <title>Document</title>
</head>
<body>
App Bar @include('components.app-bar')

<table border = "1">
		<thead>
			<th>#</th>
			<th>Client Firstname</th>
            <th>Client Middlename</th>
            <th>Client Lastname</th>
            <th>Office Use</th>
			<th>Reason Of Request</th>	
            <th>Reason Type</th>
            <th>Client ID</th>
            <th>Transaction ID</th>
            <th>Supplier ID</th>
		</thead>
		<tbody>
            @forelse($request as $counter => $row)
                <tr>								
                    <td>{{$loop->iteration}}</td> 
                    <td>{{ $row->client_lname }}</td>
                    <td>{{ $row->client_fname }}</td>
                    <td>{{ $row->client_mname }}</td>
                    <td>{{ $row->Office_use}}</td>
                    <td>{{ $row->Request_type}}</td>
                    <td>{{ $row->Reason_of_request }}</td>
                    <td>{{ $row->client_id }}</td>
                    <td>{{ $row->transaction_id }}</td>
                    <td>{{ $row->supplier_id }}</td>

                </tr>
                @empty
                    <tr>
                        <td colspan="10">No Users Found</td>
                    </tr>
            @endforelse
		</tbody>
	</table><br>
    <a href="{{url('/dashboard')}}">Back</a>
    
    <div style="width: 80%; margin: auto;">
        <canvas id="lineChart"></canvas>
    </div>
    <div style="width: 80%; margin: auto;">
        <canvas id="BARChart"></canvas>
    </div>
    <script>
        const today = @json($data['labels']);
        var ctx = document.getElementById('lineChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [today],
                datasets: [{
                    label: 'Data',
                    data: @json($data['data']),
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    fill: false
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        var BAR = document.getElementById('BARChart').getContext('2d');
        var myChart = new Chart(BAR, {
            type: 'bar',
            data: {
                labels: [today],
                datasets: [{
                    label: 'Data',
                    data: @json($data['data']),
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    fill: false
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>>

</body>
</html>