# fxj-swoole_client

#### TCP/UDP
```$xslt
    /**
     * 建立swoole客户端  默认TCP
     * @param string $serverType  类型 TCP/UDP
     * @param bool $isSync 是否同步/异步
     * @param bool $isKeep 是否长连接
     */
    $swoole = new \Swoole\BaseSwooleConnect('tcp',false,false);
```

####建立连接发送数据
 ```
 $data = [
     'name' => 'fxj',
     'age' => 18,
 ];
 $client = new \Client\TcpClient($swoole,$data);
 $res = $client->doConnect('192.168.3.10',8090);
 var_dump($res);
 ```
