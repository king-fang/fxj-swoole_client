# fxj-swoole_client
Swoole Client TCP/UDP

 ```
 $swoole = new \Swoole\BaseSwooleConnect();
 $data = [
     'name' => 'fxj',
     'age' => 18,
 ];
 $client = new \Client\TcpClient($swoole,$data,new \Swoole\SwooleCallback());
 $res = $client->doConnect('192.168.3.10',8090);
 var_dump($res);
 ```
