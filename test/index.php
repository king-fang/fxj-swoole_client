<?php

require_once '../vendor/autoload.php';

//获取swoole_clinet对象
$swoole = new \Swoole\BaseSwooleConnect('tcp',false,true);

//数据
$data = [
    'name' => 'fxj',
    'age' => 18,
];
$client = new \Client\TcpClient($swoole,$data,new \Swoole\SwooleCallback());
$res = $client->doConnect('192.168.3.10',8090);
var_dump($res);