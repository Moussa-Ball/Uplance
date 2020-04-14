<?php

namespace App\Http\Controllers;

use App\Events\UserOffline;
use App\User;

class SwitcherOfflineController extends Controller
{
    public function __construct()
    {
        $this->middleware('complete.profile');
    }

    public function __invoke(User $user)
    {
        $user->switcher_status = 'offline';
        $user->save();

        broadcast(new UserOffline($user));
    }
}
