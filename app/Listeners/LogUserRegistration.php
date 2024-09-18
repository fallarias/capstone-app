<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Logs;
use Illuminate\Auth\Events\Registered;

class LogUserRegistration

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
     *
     * @param  \Illuminate\Auth\Events\Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        // Log user registration
        Logs::create([
            'action' => 'Register',
            'account_type' => $event->user->account_type,
            'message' => 'Registered Successfully.',
            'user_id' => $event->user->user_id, // Get the user ID from the event
        ]);
    }
}
