<?php


$ws = new swoole_websocket_server("0.0.0.0", 9501);
$ws->user_c = [];


$ws->on('open', function ($ws, $request) {
    $ws->user_c[] = $request->fd;
    $ws->push($request->fd, "hello, welcome\n");
});

$ws->on('message', function ($ws, $frame) {
    $msg = 'from'.$frame->fd.":{$frame->data}\n";
    foreach ($ws->user_c as $v) {
        $ws->push($v, $msg);
    }
});

$ws->on('close', function ($ws, $fd){
   unset($ws->user_c[$fd-1]);
});

$ws->start();
