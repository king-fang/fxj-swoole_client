<?php
namespace Swoole;

abstract class SwooleDecorator extends ISwooleComponent implements ISwoole {



    /**
     * IIP地址，也可选域名
     * @var string
     */
    protected static $host = ISwoole::HOST;

    /**
     * 端口
     * @var string
     */
    protected static $port = ISwoole::POST;

    /**
     * 表示socket的类型，如TCP/UDP
     * SWOOLE_SOCK_TCP
     * SWOOLE_SOCK_UDP
     * @var string
     */
    protected static $serverType = ISwoole::SERVER_TYPE;


    /**
     * 用于获取客户端socket的本地host:port，必须在连接之后才可以使用
     * @var
     */
    protected $sockName;

    /**
     * 获取对端socket的IP地址和端口
     * UDP协议通信客户端向一台服务器发送数据包后，可能并非由此服务器向客户端发送响应
     * 可以使用getpeername方法获取实际响应的服务器IP:PORT
     * 此函数必须在$client->recv() 之后调用
     * @var
     */
    protected $peername;


    /**
     * @param string $host  远程服务器的地，也可直接传入域名
     * @param int $port 远程服务器端口
     * @param float $timeout 网络IO的超时 包括connect/send/recv 单位是s 支持浮点数。默认为0.5s，即500ms
     *
     * 1.在UDP类型时表示是否启用udp_connect 设定此选项后将绑定$host与$port，此UDP将会丢弃非指定host/port的数据包  默认为0
     * 2.参数在TCP类型，$flag=1 表示设置为非阻塞socket  默认为0 阻塞
     * @param int $flag
     * @return mixed
     */
    protected function doConnect(string $host, int $port, float $timeout = 0.5, int $flag = 0){}


    /**
     * 配置参数
     * 详见：https://wiki.swoole.com/wiki/page/p-client_setting.html
     * @var array
     */
    protected $set  = [];

//    /**
//     * 返回swoole_client的连接状态
//     * 返回false，表示当前未连接到服务器
//     * 返回true，表示当前已连接到服务器
//     * @return mixed
//     */
//    abstract protected function isConnected();


    /**
     * 用于获取客户端socket的本地host:port
     * 如：array('host' => '127.0.0.1', 'port' => 53652)
     * @return mixed
     */
    abstract protected function getPeerName();

//    /**
//     * 获取对端socket的IP地址和端口 仅支持SWOOLE_SOCK_UDP/SWOOLE_SOCK_UDP6类型的swoole_client对象 UDP
//     * 此函数必须在$client->recv() 之后调用
//     * @return mixed
//     */
//    abstract protected function getSockName();


    /**
     * 设置客户端参数，必须在connect前执行
     * 选项url地址:https://wiki.swoole.com/wiki/page/p-client_setting.html
     * @param array $option
     * @return mixed
     */
    abstract public function setOptions(array $option = []);

}