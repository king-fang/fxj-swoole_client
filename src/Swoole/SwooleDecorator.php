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
     * 配置参数
     * 详见：https://wiki.swoole.com/wiki/page/p-client_setting.html
     * @var array
     */
    protected $set  = [];

    /**
     * 客户端IP和端口 同步阻塞模式
     * @var
     */
    protected $sockname;


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
     * 设置客户端参数，必须在connect前执行
     * 选项url地址:https://wiki.swoole.com/wiki/page/p-client_setting.html
     * @param array $option
     * @return mixed
     */
    abstract public function setOptions(array $option = []);

    /**
     * 用于获取客户端socket的本地host:port，必须在连接之后才可以使用。
     * array('host' => '127.0.0.1', 'port' => 53652)
     * @return mixed
     */
    abstract protected function getSockname();
}