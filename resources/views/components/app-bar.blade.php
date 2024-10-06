<div style="width: 150px; height: 100vh; background-color: #18392B; color: white; position: fixed; top: 0; left: 0; padding: 50px; overflow-y: auto;">
    <!-- Logo at the Top -->
    <div style="text-align: center; margin-bottom: 30px;">
        <img src="{{ asset('img/isu.png') }}" alt="Logo" style="height: 50px;">
    </div>

    <!-- Links Below Logo -->
    <div style="display: flex; flex-direction: column; align-items: flex-start; padding-top: 100px;">
        <a href="{{ url('/dashboard') }}" class="icon-container {{ Request::is('dashboard') ? 'active' : '' }}" style="margin-bottom: 50px; display: flex; align-items: center; margin-left: -75px; text-decoration:none">
            <i class="fas fa-chart-pie"></i>
            <span style="margin-left: 10px; color: white;">Dashboard</span>
        </a>
        <a href="{{ url('/create/task') }}" class="icon-container {{ Request::is('create') ? 'active' : '' }}" style="margin-bottom: 50px; display: flex; align-items: center; margin-left: -70px; text-decoration:none">
            <i class="fas fa-file-alt"></i>
            <span style="margin-left: 10px; color: white;">Create Task</span>
        </a>

        <a href="{{ url('/audit') }}" class="icon-container {{ Request::is('audit') ? 'active' : '' }}" style="margin-bottom: 40px; display: flex; align-items: center; margin-left: -70px; text-decoration:none">
            <i class="fas fa-book"></i>
            <span style="margin-left: 10px; color: white;">Audit Trails</span>
        </a>
        
        <a href="{{ url('/listOfTask') }}" class="icon-container {{ Request::is('list') ? 'active' : '' }}" style="margin-bottom: 40px; display: flex; align-items: center; margin-left: -70px; text-decoration:none">
            <i class="fas fa-clipboard-list"></i>
            <span style="margin-left: 10px; color: white;">List of Available Tasks</span>
        </a>
        
        <!-- Logs Page Icon -->
        <a href="{{ url('/logs') }}" class="icon-container {{ Request::is('logs') ? 'active' : '' }}" style="margin-bottom: 40px; display: flex; align-items: center;margin-left: -70px; text-decoration:none">
            <i class="fas fa-book" style="font-size: 24px;"></i>
            <span style="margin-left: 10px; color: white;">Logs</span>
        </a>

        <!-- Logout Icon at the Bottom -->
        <div style="margin-top: 15px;">
            <a href="{{ url('/logout') }}" class="icon-container" style="display: flex; align-items: center; text-decoration: none; color: white; margin-left: -70px; text-decoration:none">
                <i class="fas fa-sign-out-alt" style="font-size: 24px;"></i>
                <span style="margin-left: 10px; font-size: 16px;">Logout</span>
            </a>
        </div>
    </div>

    <!-- Extra content to test scrolling -->
    <div style="height: 200px;"></div>
</div>