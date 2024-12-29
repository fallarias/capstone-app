<div style="width: 150px; height: 100vh; background-color: #18392B; color: white; position: fixed; top: 0; left: 0; padding: 50px; overflow-y: auto;">

    <!-- Logo at the Top -->
    <div style="text-align: center; margin-bottom: 15px;">
        <img src="{{ asset('img/isu.png') }}" alt="Logo" style="height: 80px;">
    </div>

    <div style="text-align: center; margin-bottom: 60px;">
        @if($admin)
            <span style="color: white; font-size: 18px;">
                {{ $admin->lastname }} {{ $admin->firstname }} {{ $admin->middlename }}
            </span>
        @else
            <span style="color: white; font-size: 18px;">Admin Name Not Available</span>
        @endif
    </div>
    
    <!-- Line at the Top -->
    <div style="border-top: 2px solid white; margin-bottom: -60px; margin-left: -40px;width:210px"></div>

    <!-- Links Below Logo -->
    <div style="display: flex; flex-direction: column; align-items: flex-start; padding-top: 100px;">
        <a href="{{ url('/dashboard') }}" class="icon-container {{ Request::is('dashboard') ? 'active' : '' }}" style="margin-bottom: 50px; display: flex; align-items: center; margin-left: -75px; text-decoration:none">
            <i class="fas fa-chart-pie"></i>
            <span style="margin-left: 10px; color: white; text-align: start;">Dashboard</span>
        </a>
        <a href="{{ url('/create/task') }}" class="icon-container {{ Request::is('create') ? 'active' : '' }}" style="margin-bottom: 50px; display: flex; align-items: center; margin-left: -70px; text-decoration:none">
            <i class="fas fa-file-alt"></i>
            <span style="margin-left: 10px; color: white; text-align: start;">Create Task</span>
        </a>

        <a href="{{ url('/audit') }}" class="icon-container {{ Request::is('audit') ? 'active' : '' }}" style="margin-bottom: 40px; display: flex; align-items: center; margin-left: -70px; text-decoration:none">
            <i class="fas fa-book"></i>
            <span style="margin-left: 10px; color: white; text-align: start;">Audit Trails</span>
        </a>
        
        <a href="{{ url('/listOfTask') }}" class="icon-container {{ Request::is('list') ? 'active' : '' }}" style="margin-bottom: 40px; display: flex; align-items: center; margin-left: -70px; text-decoration:none">
            <i class="fas fa-clipboard-list"></i>
            <span style="margin-left: 12px; color: white;text-align: start;">List of Available Tasks</span>
        </a>
        
        <!-- Logs Page Icon -->
        <a href="{{ url('/logs') }}" class="icon-container {{ Request::is('logs') ? 'active' : '' }}" style="margin-bottom: 40px; display: flex; align-items: center;margin-left: -70px; text-decoration:none">
            <i class="fas fa-history " style="font-size: 24px;"></i>
            <span style="margin-left: 10px; color: white;text-align: start;">Logs</span>
        </a>

        <!--Holiday Page Icon -->
        <a href="{{ url('/admin/holiday') }}" class="icon-container {{ Request::is('admin') ? 'active' : '' }}" style="margin-bottom: 40px; display: flex; align-items: center;margin-left: -70px; text-decoration:none">
            <i class="fas fa-umbrella-beach " style="font-size: 24px;"></i>
            <span style="margin-left: 10px; color: white;text-align: start;">Create Holiday</span>
        </a>

    <!-- Line at the Top -->
    <div style="border-top: 2px solid white; margin-bottom: 40px; margin-left: -40px;width:210px"></div>

<!-- Logout Icon at the Bottom -->
<div style="margin-top: 15px;">
    <a href="{{ url('/logout') }}" class="icon-container" style="display: flex; justify-content: center; align-items: center; text-decoration: none; color: white; margin-left: -10px;   background-color: #28a745; /* Green background */
; padding: 10px 20px; border-radius: 5px; font-size: 16px; cursor: pointer; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);">
        <i class="fas fa-sign-out-alt" style="font-size: 24px; margin-left: -10px;"></i>
        <span>Logout</span>
    </a>
</div>


    </div> 

    <!-- Extra content to test scrolling -->
    <div style="height: 200px;"></div>
</div>
