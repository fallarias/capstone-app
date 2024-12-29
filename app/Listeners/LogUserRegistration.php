<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Logs;
use App\Events\UserLoggedRegistered;

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
    public function handle(UserLoggedRegistered $event)
    {
        // Log user registration
        Logs::create([
            'action' => 'Register',
            'account_type' => $event->register->account_type,
            'message' => 'Registered Successfully.',
            'user_id' => $event->register->user_id, // Get the user ID from the event
        ]);
    }
}
