<?php

namespace Client;

use Swoole\SwooleDecorator;

class UpdClient extends SwooleDecorator {


    public function __construct()
    {

    }

    public static function doConnect(string $host, int $port, float $timeout = 0.5, int $flag = 0)
    {

    }

    public function isConnected()
    {
        // TODO: Implement isConnected() method.
    }

    public function getPeerName()
    {

    }

    public function getSockName()
    {
        // TODO: Implement getSockName() method.
    }

    public function send(){

    }
}