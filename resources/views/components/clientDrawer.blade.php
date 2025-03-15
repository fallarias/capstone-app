<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISUCANNER</title>
    <style>
        /* Sidebar Styles */
        #sidebar {
            width: 250px;
            height: 100vh;
            background: linear-gradient(to bottom, #18392B, #18392B, #00b894);
            color: white;
            position: fixed;
            top: 0;
            left: 0; /* Sidebar starts in view */
            transition: left 0.3s ease;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Sidebar Content */
        .sidebar-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar-header img {
            height: 80px;
            border-radius: 50%;
        }

        .sidebar-header h2 {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .sidebar-header p {
            font-size: 0.9rem;
            margin: 0;
            color: #d1f2eb;
        }

        /* Menu Item Styles */
        .icon-container {
            width: 100%;
            background-color: white;
            color: #18392B;
            text-decoration: none;
            font-size: 1rem;
            display: flex;
            align-items: center;
            margin: 10px 0;
            padding: 10px 15px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .icon-container i {
            margin-right: 10px;
            color: #00b894;
        }

        .icon-container:hover {
            background-color: #d1f2eb;
            text-decoration: none;
        }

        .app-title {
            display: flex;
            align-items: center;
            margin-left: 260px; /* Adjust margin for sidebar */
            transition: margin-left 0.3s ease;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .title2 {
            color:#00b894;
            margin-top: -100px;
            margin-left: 95px;
            font-size: 74px;
            font-weight: bold;
        }
        .clientDrawer{
            color:black;
        }

    </style>
</head>
<body>
    <div id="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('img/isu.png') }}" alt="Logo">
            <h2>ISU-CANNER</h2>
            <p>Online</p>
        </div>
        <a href="{{ url('/client/home') }}" class="icon-container">
            <i class="fas fa-home"></i>
            <span class="clientDrawer">Home</span>
        </a>
        <a href="{{ url('/client/notification') }}" class="icon-container">
            <i class="fas fa-bell"></i>
            <span class="clientDrawer">Notification</span>
        </a>
        <a href="{{ url('/client/template') }}" class="icon-container">
            <i class="fas fa-file-alt"></i>
            <span class="clientDrawer">Template</span>
        </a>
        <a href="{{ url('/client/task/list') }}" class="icon-container">
            <i class="fas fa-file-upload"></i>
            <span class="clientDrawer">Track Document</span>
        </a>
        <a href="{{ url('/client/transaction') }}" class="icon-container">
            <i class="fas fa-history"></i>
            <span class="clientDrawer">Transaction History</span>
        </a>
        <a href="{{ url('/logout/client') }}" class="icon-container" style="background-color: white; color: green;">
            <i class="fas fa-sign-out-alt"></i>
            <span class="clientDrawer">Logout</span>
        </a>
    </div>

    <div class="app-title">
        <div class="title2">CLIENT PORTAL</div>
    </div>

    <script>
        // Add event listeners to sidebar links to close the sidebar
        const links = document.querySelectorAll('#sidebar .icon-container');
        links.forEach(link => {
            link.addEventListener('click', function() {
                // Optionally navigate to the link and close the sidebar
                // For now, it will just log the link's href
                console.log('Navigating to:', this.href);
                // Close sidebar functionality can be added here if needed
            });
        });
    </script>
</body>
</html>
