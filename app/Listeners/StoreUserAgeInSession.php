<?php

namespace App\Listeners;

use Carbon\Carbon;
use Illuminate\Auth\Events\Login;

class StoreUserAgeInSession
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
    public function handle(Login $event)
    {
        $user = $event->user;
        if ($user->birthdate) {
            $age = Carbon::parse($user->birthdate)->age;
            session(['user_age' => $age]);
        }
    }
}
