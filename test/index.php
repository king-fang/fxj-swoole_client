<?php

require_once '../vendor/autoload.php';


//获取swoole_clinet对象
$swoole = new \Swoole\BaseSwooleConnect('tcp',false,false);

//数据
$data = [
    'name' => 'fxj',
    'age' => 18,
];

$client = new \Client\TcpClient($swoole,$data,new \Swoole\SwooleCallback());
$res = $client->doConnect('127.0.0.1',9501);
var_dump($res);
