<div class="stat-container" style="margin-left: 0px;">
    <div class="stat-item" style="margin-right: 50px;">
        <a href="{{url('/user')}}">
            <h4>Users</h4>
            <span id="user-dot" class="green-dot" style="display: none;"></span>
            <p id="user-count">{{ $user }}</p>
            
        </a>
    </div>
    <div class="stat-item" style="margin-right: 40px;">
        <a href="{{url('/completed/transaction')}}">
            <h4 style="width: 130px;">Completed Task</h4>
            <span id="completed-dot" class="green-dot" style="display: none;"></span>
            <p id="completed-count">{{ $completed }}</p>
            
        </a>
    </div>
    <div class="stat-item" style="margin-right: 45px;">
        <a href="{{url('/transaction')}}">
            <h4 style="width: 130px;">Transactions</h4>
            <span id="transaction-dot" class="green-dot" style="display: none;"></span>
            <p id="transaction-count">{{ $transaction }}</p>
            
        </a>
    </div>

</div>

<style>
    /* Style for the green dot */
    .green-dot {
        width: 10px;
        height: 10px;
        background-color: green;
        border-radius: 50%;
        margin-left: 5px;
        display: inline-block;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Store initial values
    let previousCounts = {
        user: {{ $user }},
        completed: {{ $completed }},
        transaction: {{ $transaction }},
        activate: {{ $activate }}
    };

    function refreshStats() {
        $.ajax({
            url: '/dashboard/stats',
            method: 'GET',
            success: function(data) {
                // Update the counts and show green dots if the value has increased
                if (data.user > previousCounts.user) {
                    $('#user-dot').show();
                } else {
                    $('#user-dot').hide();
                }
                if (data.completed > previousCounts.completed) {
                    $('#completed-dot').show();
                } else {
                    $('#completed-dot').hide();
                }
                if (data.transaction > previousCounts.transaction) {
                    $('#transaction-dot').show();
                } else {
                    $('#transaction-dot').hide();
                }
                if (data.activate > previousCounts.activate) {
                    $('#activated-dot').show();
                } else {
                    $('#activated-dot').hide();
                }

                // Update the text values
                $('#user-count').text(data.user);
                $('#completed-count').text(data.completed);
                $('#transaction-count').text(data.transaction);

                // Update previous counts
                previousCounts = {
                    user: data.user,
                    completed: data.completed,
                    transaction: data.transaction,
                    activate: data.activate
                };
            }
        });
    }

    // Refresh the stats every 5 seconds
    setInterval(refreshStats, 5000);
</script>
