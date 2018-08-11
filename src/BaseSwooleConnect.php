<?php
include_once('ISwooleComponent.php');
include_once('ISwoole.php');
include_once('SwooleClinetException.php');

class BaseSwooleConnect extends ISwooleComponent  implements ISwoole{

    /**
     * IIP地址，也可选域名
     * @var string
     */
    private static $host = ISwoole::HOST;

    /**
     * 端口
     * @var string
     */
    private static $port = ISwoole::POST;

    /**
     * 表示socket的类型，如TCP/UDP
     * SWOOLE_SOCK_TCP
     * SWOOLE_SOCK_UDP
     * @var string
     */
    private static $serverType = ISwoole::SERVER_TYPE;

    /**
     * 表示同步阻塞还是异步非阻塞，默认为同步阻塞
     * SWOOLE_SOCK_SYNC  同步客户端  false 为同步阻塞
     * SWOOLE_SOCK_ASYNC 异步客户端  true 为异步非阻塞
     * @var bool
     */
    private static $isSync;

    /**
     * 是否保持长连接
     * SWOOLE_KEEP 长连接 只允许用于同步客户端
     * swoole_client在unset时会自动调用close方法关闭socket
     * @var
     */
    private static $isKeep;

    //当前链接资源句柄
    private static $hookup;

    //设置客户端参数，必须在connect前执行
    private static $set = [];

    /**
     * 建立swoole客户端链接
     * BaseSwooleConnect constructor.
     * @param string $serverType  链接类型 TCP/UDP
     * @param bool $isSync 是否同步/异步
     * @param bool $isKeep 是否长连接
     * @throws SwooleClinetException
     */
    public function __construct($serverType = 'tcp',$isSync = false,$isKeep = false)
    {
        //异步 且 长连接 抛出异常
        if($isSync && $isKeep){
            //swoole长连接只支持同步客户端
            throw new SwooleClinetException('Swoole long connections only support synchronous clients.');
        }
        if(!in_array($serverType,array_keys(self::$serverType)))
        {
            throw new SwooleClinetException('Only TCP/UDP is allowed to create links.');
        }
        //设置异步处理 默认同步
        $isSync ?  self::$isSync = SWOOLE_SOCK_ASYNC :  self::$isSync = SWOOLE_SOCK_SYNC;
        //设置长连接 只允许用于同步客户端
        $isKeep ?  self::$isKeep = SWOOLE_KEEP :  self::$isSync = false;

        self::$hookup = new swoole_client(self::$serverType[$serverType] | self::$isKeep,self::$isSync);
    }


    /**
     * 链接服务器
     * @param string $host  远程服务器的地址，也可直接传入域名
     * @param int $port 远程服务器端口
     * @param float $timeout 网络IO的超时 包括connect/send/recv 单位是s 支持浮点数。默认为0.5s，即500ms
     *
     * 1.在UDP类型时表示是否启用udp_connect 设定此选项后将绑定$host与$port，此UDP将会丢弃非指定host/port的数据包  默认为0
     * 2.参数在TCP类型，$flag=1表示设置为非阻塞socket，connect会立即返回。如果将$flag设置为1，那么在send/recv前必须使用swoole_client_select来检测是否完成了连接
     * @param int $flag
     * @throws SwooleClinetException
     */
    public static function doConnect(string $host = null, int $port = 0, float $timeout = 0.5, int $flag = 0)
    {
        //设置参数 https://wiki.swoole.com/wiki/page/p-client_setting.html
        if(empty(self::$set))
        {
            self::$hookup->set(self::$set);
        }
        //建立链接
        if (self::$hookup->connect($host??self::$host, $port == 0 ?? self::$port,$timeout,$flag)) {
            if(self::$isSync == SWOOLE_SOCK_ASYNC)
            {
                //connect会立即返回true。但实际上连接并未建立。
                //所以不能在connect后使用send。通过isConnected()判断也是false。当连接成功后，系统会自动回调onConnect。
                //这时才可以使用send向服务器发送数据
                if(self::$hookup->isConnected()){
                    self::$hookup->send("test");
                }
            }else{
                self::$hookup->send("test");
            }
        } else {
            //失败重连 5 次
            $i = 0;
            while ($i < 5){
                //启用SWOOLE_KEEP长连接后，close调用的第一个参数要设置为true表示强行销毁长连接socket
                if(self::$isKeep == SWOOLE_KEEP)
                {
                    self::$hookup->close(true);
                }
                self::$hookup->connect($host??self::$host, $port == 0 ?? self::$port,$timeout,$flag);
                $i++;
                echo 'Client reconnection...';
            }
            throw new SwooleClinetException('Connect failed.');
        }
    }


    /**
     * 设置客户端参数，必须在connect前执行
     * 选项url地址:https://wiki.swoole.com/wiki/page/p-client_setting.html
     * @param array $option
     * @return mixed
     */
    public function set(array $option = [])
    {
        self::$set = $option;
    }
}