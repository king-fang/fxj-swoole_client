<?php

namespace Swoole;

class BaseSwooleConnect extends ISwooleComponent{

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


    /**
     * 当前客户端资源句柄
     * @var \swoole_client
     */
    private static $hookup;

    /**
     * 客户端类型
     * @var array
     */
    private static $serverType = ISwoole::SERVER_TYPE;

    /**
     * 建立swoole客户端  默认TCP
     * BaseSwooleConnect constructor.
     * @param string $serverType  类型 TCP/UDP
     * @param bool $isSync 是否同步/异步
     * @param bool $isKeep 是否长连接
     */
    public function __construct($serverType = 'tcp',$isSync = false,$isKeep = false)
    {
        //异步 且 长连接 抛出异常 长连接只支持同步阻塞模式
        if($isSync && $isKeep){
            //swoole长连接只支持同步客户端
            throw new \InvalidArgumentException('Swoole long connections only support synchronous clients.');
        }
        //是否TCP/UDP
        if(!in_array($serverType,array_keys(self::$serverType)))
        {
            throw new \InvalidArgumentException('Only TCP/UDP is allowed to create links.');
        }
        //设置异步处理 默认同步
        $isSync === true ?  self::$isSync = SWOOLE_SOCK_ASYNC :  self::$isSync = SWOOLE_SOCK_SYNC;
        //设置长连接 只允许用于同步客户端
        $isKeep === true ?  self::$isKeep = SWOOLE_KEEP :  self::$isKeep = false;
        self::$hookup = new \swoole_client(self::$serverType[$serverType] | self::$isKeep,self::$isSync);
    }

    /**
     * 获取是否同步/异步阻塞  默认返回false
     * @return bool
     */
    public function getIsSync()
    {
        if(self::$isSync > 0)
        {
            return true;
        }
        return false;
    }

    /**
     *   默认返回false
     * @return bool
     */
    public function getIsKeep()
    {
        if(self::$isKeep > 0)
        {
            return true;
        }
        return self::$isKeep;
    }

    /**
     * 获取 swoole_client对象
     * @return \swoole_client
     */
    public function getHookup()
    {
        return self::$hookup;
    }

   protected function doConnect(string $host, int $port, float $timeout = 0.5, int $flag = 0){}
}