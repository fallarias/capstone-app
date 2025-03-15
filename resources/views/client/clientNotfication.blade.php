<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .app-bar {
            background-color: #18392B;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 96px;
            padding-right: 20px;
            padding-left: 80px;
        }

        .app-bar .title {
            font-size: 74px;
            font-weight: bold;
            margin: 0;
        }

        .app-bar .nav-links {
            display: flex;
            gap: 50px;
        }

        .app-bar .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            margin-bottom: -5px;
        }

        .app-bar .nav-links a:hover {
            text-decoration: underline;
        }

        .container {
            margin-top: 20px;
            padding: 20px;
        }

        .img-person {
            width: 60px; 
            height: 60px; 
            border-radius: 50%; 
            margin-bottom: -10px; 
            border: 4px solid rgb(3, 170, 67);
        }

        .custom-search-button {
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .custom-search-button:hover {
            background-color: #45a049;
        }

        .email-container {
            max-width: 1300px;
            margin-top: 20px auto;
            margin-left: 252px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .email-row {
            display: flex;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
            transition: background-color 0.3s;
            cursor: pointer;
        }

        .email-row:hover {
            background-color: #f9f9f9;
        }

        .email-sender {
            width: 28%;
            font-weight: bold;
            color: #333;
        }
        .email-sender1 {
            width: 20%;
            color: #888;
        }

        .email-subject {
            width: 15%;
            color: #555;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .email-subject1 {
            width: 40%;
            color: #555;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .email-date {
            width: 30%;
            text-align: right;
            color: #888;
        }
        .email-date1 {
            width: 12%;
            text-align: right;
            color: #888;
        }

        .email-time {
            width: 32%;
            text-align: right;
            color: #888;
        }
        .email-time1 {
            width: 10%;
            text-align: right;
            color: #888;
        }

        .email-owner {
            margin-left: 100px;
            text-align: right;
            color: #888;
        }
        .email-header {
            display: flex;
            align-items: center;
            padding: 15px;
            font-weight: bold;
            background-color: #f1f1f1;
            border-bottom: 2px solid #ccc;
        }
        .notification-header{
            display: flex;
            align-items: center;
            padding: 15px;
            font-weight: bold;
            background-color: #f1f1f1;
            border-bottom: 2px solid #ccc;
            margin-left: 250px;
        }
    </style>
</head>
<body>
<div class="app-bar">
    <!--
    <div class="search-container">
        <form class="form-inline" action="/search" method="GET">
            <input type="text" name="query" class="form-control mr-sm-2" placeholder="Search" aria-label="Search" style="margin-left:1100px">
            <button class="btn custom-search-button" type="submit">
                <i class="fas fa-search" style="font-size: 20px;"></i> Search
            </button>
        </form>
    </div>
    -->
</div>

@include('components.clientDrawer')


<div class="notification-header">
    <h2>All Notifications ({{ $totalNotifications }})</h2>
</div>

<div class="email-container">
    <div class="email-header">
        <div class="email-sender1">All</div>
        <div class="email-owner">Name</div>
        <div class="email-time">Time</div>
        <div class="email-date">Date</div>
    </div>

    @foreach ($auditEntry as $time)
        <div class="email-row" onclick="showModal('Accepted Beyond 5 PM', 'The task will be continued at 8:30 AM for Transaction ID Number {{$time->transaction_id}}.')">
            <div class="email-sender">{{ $time->staff->lastname }}, {{ $time->staff->firstname }}</div>
            <div class="email-subject">{{ $time->office_name }} </div>
            <div class="email-subject1">{{ $time->office_name }} - The Transaction ID Number {{$time->transaction_id}} is accepted at {{ \Carbon\Carbon::parse($time->start)->format('g:i A') ?? 'No description available' }}</div>
            <div class="email-date1">{{ \Carbon\Carbon::parse($time->created_at)->format('M d') }}</div>
        </div>
    @endforeach

    @foreach ($finishedAudits as $overtime)
        @if(($overtime->finished > $overtime->deadline) || (empty($overtime->finished) && now() > $overtime->deadline))
            <div class="email-row" onclick="showModal('New Message', 'The task {{ $overtime->task }} is finished at {{ \Carbon\Carbon::parse($overtime->finished)->format('g:i A') ?? 'No description available' }} exceeding the deadline at {{ \Carbon\Carbon::parse($overtime->deadline)->format('g:i A') ?? 'No description available' }} for transaction number {{$overtime->transaction_id}}.')">
                <div class="email-sender">{{ $overtime->staff->lastname }}, {{ $overtime->staff->firstname }}</div>
                <div class="email-subject">{{ $overtime->office_name }} </div>
                <div class="email-subject1">The task {{ $overtime->task }} is finished at {{ \Carbon\Carbon::parse($overtime->finished)->format('g:i A') ?? 'No description available' }} exceeding the deadline at {{ \Carbon\Carbon::parse($overtime->deadline)->format('g:i A') ?? 'No description available' }} for Transaction ID Number {{$overtime->transaction_id}}</div>
                <div class="email-date1">{{ \Carbon\Carbon::parse($overtime->finished)->format('M d') }}</div>
            </div>
        @endif
    @endforeach

    @foreach ($finishedAudits as $finish)
        @if($finish->finished === null)
            <div class="email-row" onclick="showModal('New Message', 'The task {{ $finish->task }} is started at {{ \Carbon\Carbon::parse($finish->start)->format('g:i A') ?? 'No description available' }} for transaction number {{$finish->transaction_id}}.')">
                <div class="email-sender">{{ $finish->staff->lastname }}, {{ $finish->staff->firstname }}</div>
                <div class="email-subject">{{ $finish->office_name }}</div>
                <div class="email-subject1">The task {{ $finish->task }} is started at {{ \Carbon\Carbon::parse($finish->start)->format('g:i A') ?? 'No description available' }} deadline at {{ \Carbon\Carbon::parse($finish->deadline)->format('g:i A') ?? 'No description available' }} for Transaction ID Number {{$finish->transaction_id}}</div>
                <div class="email-date1">{{ \Carbon\Carbon::parse($finish->finished)->format('M d') }}</div>
            </div>
        @else
            <div class="email-row" onclick="showModal('Task Completed', 'The task {{ $finish->task }} is finished at {{ \Carbon\Carbon::parse($finish->finished)->format('g:i A') ?? 'No description available' }} for Transaction ID Number {{$finish->transaction_id}}.')">
                <div class="email-sender">{{ $finish->staff->lastname }}, {{ $finish->staff->firstname }}</div>
                <div class="email-subject">{{ $finish->office_name }}</div>
                <div class="email-subject1">Task Completed - The task {{ $finish->task }} is finished at {{ \Carbon\Carbon::parse($finish->finished)->format('g:i A') ?? 'No description available' }} for Transaction ID Number {{$finish->transaction_id}}</div>
                <div class="email-date1">{{ \Carbon\Carbon::parse($finish->finished)->format('M d') }}</div>
            </div>
        @endif
    @endforeach

    @foreach ($requirementMessages as $message)
        <div class="email-row" onclick="showModal('{{ $message->department }}', '{{ $message->message }}.')">
            <div class="email-sender">{{ $message->staff->lastname }}, {{ $message->staff->firstname }}</div>
            <div class="email-subject">Lack of Requirement - For Transaction ID Number {{$message->transaction_id}}</div>
            <div class="email-date">{{ \Carbon\Carbon::parse($message->created_at)->format('M d') }}</div>
        </div>
    @endforeach
</div>



<!-- Bootstrap Modal -->
<div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="messageModalLabel">Message Title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="messageModalBody">
                Message content goes here.
            </div>

        </div>
    </div>
</div>

<script>
    function showModal(title, message) {
        document.getElementById('messageModalLabel').innerText = title;
        document.getElementById('messageModalBody').innerText = message;
        $('#messageModal').modal('show');
    }
</script>

</body>
</html>