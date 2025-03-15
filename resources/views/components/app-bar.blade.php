


<div class="side-menu">

    <!-- Logo at the Top -->
    <div class="head-menu">
        <img src="{{ asset('img/isu.png') }}" alt="Logo" style="height: 50px;">
    </div>

    <div class="head-text">
        @if($admin)
            <span class="menu-text">
                {{ $admin->firstname }} {{ $admin->middlename }}. {{ $admin->lastname }} 
            </span>
        @else
            <span style="color: white; font-size: 18px;">Admin Name Not Available</span>
        @endif
    </div>
    
    <!-- Line at the Top -->
    <div class="line-up"></div>

    <!-- Links Below Logo -->
    <div style="display: flex; flex-direction: column; align-items: flex-start; padding-top: 90px;">
        <a href="{{ url('/dashboard') }}" class="icon-container {{ Request::is('dashboard') ? 'active' : '' }}" style="margin-bottom: 40px; display: flex; align-items: center; margin-left: -75px; text-decoration:none">
            <i class="fas fa-chart-pie"></i>
            <span >Dashboard</span>
        </a>
        <a href="{{ url('/create/task') }}" class="icon-container {{ Request::is('create') ? 'active' : '' }}" style="margin-bottom: 40px; display: flex; align-items: center; margin-left: -70px; text-decoration:none">
            <i class="fas fa-file-alt"></i>
            <span >Create Task</span>
        </a>

        <a href="{{ url('/audit') }}" class="icon-container {{ Request::is('audit') ? 'active' : '' }}" style="margin-bottom: 40px; display: flex; align-items: center; margin-left: -70px; text-decoration:none">
            <i class="fas fa-book"></i>
            <span >Audit Trails</span>
        </a>
        
        <a href="{{ url('/listOfTask') }}" class="icon-container {{ Request::is('list') ? 'active' : '' }}" style="margin-bottom: 40px; display: flex; align-items: center; margin-left: -70px; text-decoration:none">
            <i class="fas fa-clipboard-list"></i>
            <span>List of Available Tasks</span>
        </a>
        
        <!-- Logs Page Icon -->
        <a href="{{ url('/logs') }}" class="icon-container {{ Request::is('logs') ? 'active' : '' }}" style="margin-bottom: 40px; display: flex; align-items: center;margin-left: -70px; text-decoration:none">
            <i class="fas fa-history " ></i>
            <span >Logs</span>
        </a>

        <!--Holiday Page Icon -->
        <a href="{{ url('/admin/holiday') }}" class="icon-container {{ Request::is('admin') ? 'active' : '' }}" style="margin-bottom: 40px; display: flex; align-items: center;margin-left: -70px; text-decoration:none">
            <i class="fas fa-umbrella-beach " ></i>
            <span>Create Holiday</span>
        </a>

    <!-- Line at the Top -->
    <div class="line-down"></div>

<!-- Logout Icon at the Bottom -->
<div style="margin-top: 5px;">
    <a href="{{ url('/logout') }}" class="icon-container btn-logout">
        <span>Logout</span>
    </a>
</div>


    </div> 

    <!-- Extra content to test scrolling -->
    <div style="height: 200px;"></div>
</div>
