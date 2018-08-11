<?php

namespace Client;

use Swoole\SwooleDecorator;

class UpdClient extends SwooleDecorator {

    /**
     * 获取对端socket的IP地址和端口
     * UDP协议通信客户端向一台服务器发送数据包后，可能并非由此服务器向客户端发送响应
     * 可以使用getpeername方法获取实际响应的服务器IP:PORT
     * 此函数必须在$client->recv() 之后调用
     * @var
     */
    protected $peername;



    public function __construct()
    {

    }

    public function doConnect(string $host, int $port, float $timeout = 0.5, int $flag = 0)
    {

    }

    /**
     * 获取对端socket的IP地址和端口 仅支持SWOOLE_SOCK_UDP/SWOOLE_SOCK_UDP6类型的swoole_client对象 UDP
     * 此函数必须在$client->recv() 之后调用
     * UDP协议通信客户端向一台服务器发送数据包后，可能并非由此服务器向客户端发送响应
     * 可以使用getpeername方法获取实际响应的服务器IP:PORT。
     * @return mixed
     */
    public function getPeerName(){
        return $this->peername;
    }


    /**
     * 用于获取客户端socket的本地host:port，必须在连接之后才可以使用。
     * array('host' => '127.0.0.1', 'port' => 53652)
     * @return mixed
     */
    public function getSockName(){
        return $this->peername;
    }

    /**
     * 设置参数
     * @param array $option
     * @return array
     */
    public function setOptions(array $option = [])
    {
        return $this->set = $option;
    }
}