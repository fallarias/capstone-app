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
            'action' => 'register',
            'message' => 'User registered successfully.',
            'user_id' => $event->user->user_id, // Correct user ID reference
        ]);
    }
}
