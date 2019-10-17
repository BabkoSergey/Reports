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

Broadcast::channel('estimates.{estimateId}', function ($user, $estimateId) {
    return true;
//    return $user->id === User::findOrNew($orderId)->user_id;
});

Broadcast::channel('chat', function ($user) {
    $user->avatar = $user->getUserAvatar();
    $user->fullName = $user->getFullName();
    $user->labelName = $user->getFullNameAbr();
    $user->shortName = $user->getShortFullName();
    
    return $user;
});