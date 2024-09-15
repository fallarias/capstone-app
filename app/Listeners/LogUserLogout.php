<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Logout;
use App\Models\Logs;
use App\Events\UserLoggedOut;

class LogUserLogout
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
    public function handle(UserLoggedOut $event)
    {

        // Check if the user object is present
        if ($event->user) {
            Logs::create([
                'action' => 'Logout',
                'message' => $event->user->account_type . ' logged out successfully.',
                'user_id' => $event->user->user_id,
            ]);
        } else {
            // Handle case where user is null
            Logs::create([
                'action' => 'Logout',
                'message' => 'User logged out, but user information is missing.',
                'user_id' => null,
            ]);
        }
    }
}
