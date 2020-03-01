<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/


Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('Job', function () {
    return Auth::check();
});

Broadcast::channel('uplance', function ($user) {
    return $user->hashid;
});

Broadcast::channel('messenger', function ($user) {
    return Auth::check();
});
