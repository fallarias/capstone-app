<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\UserLoggedIn;
use App\Models\Logs;

class LogUserLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserLoggedIn $event)
    {
        Logs::create([
            'action' => 'Login',
            'account_type' => $event->user->account_type,
            'message' => 'Logged In Successfully',
            'user_id' => $event->user->user_id, // Get the user ID from the event
        ]);
        
    }
}
