<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;

class RedisTestController extends Controller
{
    public function heyrediswhatsup()
    {
        Redis::set('ping', 'pong');

        return Redis::get('ping');
    }
}
