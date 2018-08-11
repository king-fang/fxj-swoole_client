<?php
namespace Swoole;

class SwooleCallback implements ISwooleCallback {

    private $data;

    public function __construct($data = null)
    {
        $this->data = $data;
    }

    public function onConnect($cli)
    {
        //是否成功链接
        if($cli->isConnected())
        {
            echo "swoole_client Asynchronous link successfully.\n";
            $cli->send(json_encode($this->data));
        }else{
            echo "swoole_client Asynchronous link failure.\n";
        }
    }

    public function onReceive($cli, $data)
    {
        echo $data.PHP_EOL;
        sleep(2);
        $cli->send(json_encode($this->data));
    }

    //链接关闭
    public function onClose($cli)
    {
        echo "swoole_client Asynchronous link close.\n";
        Read::write("swoole_client Asynchronous link close.");
    }

    //异步链接失败
    public function onError($cli)
    {
        echo "swoole_client Asynchronous link failure.\n";
        Read::write("swoole_client Asynchronous link failure.");
    }

}