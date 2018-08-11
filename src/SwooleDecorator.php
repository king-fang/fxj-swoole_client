<?php
include_once('ISwooleComponent.php');
include_once('ISwoole.php');

abstract class SwooleDecorator extends ISwooleComponent{

    //调用此方法可以得到底层的socket句柄，返回的对象为sockets资源句柄。
    protected $socket;

    //用于获取客户端socket的本地host:port，必须在连接之后才可以使用
    protected $sockName;

    //获取对端socket的IP地址和端口
    //UDP协议通信客户端向一台服务器发送数据包后，可能并非由此服务器向客户端发送响应。
    //可以使用getpeername方法获取实际响应的服务器IP:PORT
    //此函数必须在$client->recv() 之后调用
    protected $peername;


    /**
     * 返回swoole_client的连接状态
     * 返回false，表示当前未连接到服务器
     * 返回true，表示当前已连接到服务器
     * @return mixed
     */
    abstract protected function isConnected();


    //用于获取客户端socket的本地host:port
    //如：array('host' => '127.0.0.1', 'port' => 53652)
    abstract protected function getPeerName();

    //获取对端socket的IP地址和端口 仅支持SWOOLE_SOCK_UDP/SWOOLE_SOCK_UDP6类型的swoole_client对象 UDP
    //此函数必须在$client->recv() 之后调用
    abstract protected function getSockName();


    abstract public function send();

}