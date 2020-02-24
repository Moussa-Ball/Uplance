<?php

namespace App\Http\Controllers;

use App\User;
use App\Events\UserInactif;

class UserInactifController extends Controller
{
    public function __invoke(User $user)
    {
        $user->status = 'offline';
        $user->save();

        broadcast(new UserInactif($user));
    }
}
