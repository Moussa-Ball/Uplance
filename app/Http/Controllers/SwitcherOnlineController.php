<?php

namespace App\Http\Controllers;

use App\Events\UserOnline;
use App\User;

class SwitcherOnlineController extends Controller
{
    public function __invoke(User $user)
    {
        $user->switcher_status = 'online';
        $user->save();

        broadcast(new UserOnline($user));
    }
}
