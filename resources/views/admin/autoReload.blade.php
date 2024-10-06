<div class="stat-container" style="margin-left: 0px;">
           <!-- <div class="stat-item" style="margin-right: 40px;">
                <a href="{{url('/supplier')}}">
                    <h4 style="width: 130px;">Suppliers</h4>
                    <p id="supplier-count">{{ $supplier }}</p>
                </a>
            </div>
            -->
            <div class="stat-item" style="margin-right: 50px;">
                <a href="{{url('/user')}}">
                    <h4>Users</h4>
                    <p id="user-count">{{ $user }}</p>
                </a>
            </div>
            <div class="stat-item" style="margin-right: 45px;">
                <a href="{{url('/transaction')}}">
                    <h4 style="width: 130px;">Transactions</h4>
                    <p id="transaction-count">{{ $transaction }}</p>
                </a>
            </div>
            <div class="stat-item" style=" display: flex;align-items: center;height: 115px;">
                <a href="{{url('/activated/task')}}">
                    <h4 style="width: 130px;">Activated Task</h4>
                    <p id="activated-task--count">{{ $activate }}</p>
                </a>
            </div>
        </div>

<!-- Add jQuery for AJAX calls -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Function to refresh the stats every 5 seconds
    function refreshStats() {
        $.ajax({
            url: '/dashboard/stats', // The route where you fetch updated stats
            method: 'GET',
            success: function(data) {
                // Update the counts with the fetched data
                $('#supplier-count').text(data.supplier);
                $('#user-count').text(data.user);
                $('#transaction-count').text(data.transaction);
                $('#activated-task-count').text(data.activate);
            }
        });
    }

    // Refresh the stats every 5 seconds (5000 milliseconds)
    setInterval(refreshStats, 5000);
</script>
