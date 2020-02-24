<?php

namespace App\Http\Controllers;

use App\User;
use App\Events\UserOnline;

/**
 * Allows to set the user presence.
 *
 * @param \Illuminate\Http\Request
 */
class UserOnlineController extends Controller
{
    public function __invoke(User $user)
    {
        $user->status = 'online';
        $user->save();

        broadcast(new UserOnline($user));
    }
}
