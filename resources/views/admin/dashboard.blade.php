<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h1>Admin</h1>
    <a href="{{url('/create')}}">Create New Task</a><br>
    <a href="{{url('/list')}}">Edit Task</a><br>
    <a href="{{url('/list')}}">list of available task</a><br>
    <a href="{{url('/qrcode')}}">QR Code</a><br>
    <a href="{{url('/request')}}">Requests Data</a><br>

    <table border = 1>
        <tr>
            <th>total of suppliers</th>
            <th>total of users</th>
            <th>total of Transaction</th>
            <th>Number of Clients</th>
        </tr>
        <tr>
            <td>{{ $supplier }}<br>
            <a href="{{url('/supplier')}}">view all</a></td>
            <td>{{ $user }}<br>
            <a href="{{url('/user')}}">view all</a>
            </td>
            <td>{{$transaction}}<br>
            <a href="{{url('/transaction')}}">view all</a>
            </td>
            <td>{{$client}}<br>
            <a href="{{url('/clients')}}">view all</a>
            </td>
        </tr>
    </table>
    <a href="{{url('/logout')}}">logout</a>
</body>
</html>