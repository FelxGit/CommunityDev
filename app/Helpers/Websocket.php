<?php

namespace App\Helpers;

use Illuminate\Broadcasting\Broadcasters\PusherBroadcaster;
use Illuminate\Http\Request;
use Pusher\Pusher;

class Websocket
{
    /*======================================================================
     * CONSTANTS
     *======================================================================*/
    /*======================================================================
     * STATIC METHODS
     *======================================================================*/

     /**
     * Websocket::connect()
     * return a concatenated by lang/locale by space (" ")
     *
     * @param String,String,Array
     * @return PusherBroadcaster
     */
     public static function connect()
     {
        $pusher = new Pusher(
          config('broadcasting.connections.pusher.key', []),
          config('broadcasting.connections.pusher.secret', []),
          config('broadcasting.connections.pusher.app_id', []),
          config('broadcasting.connections.pusher.options', [])
        );

        return new PusherBroadcaster($pusher);
     }
}
