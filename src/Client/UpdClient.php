<?php

namespace Client;

use Swoole\Mysql\Exception;
use Swoole\Read;
use Swoole\SwooleDecorator;
use Swoole\ISwooleCallback;
use Swoole\ISwooleComponent;

class UpdClient extends SwooleDecorator {

    /**
     * 获取对端socket的IP地址和端口
     * UDP协议通信客户端向一台服务器发送数据包后，可能并非由此服务器向客户端发送响应
     * 可以使用getpeername方法获取实际响应的服务器IP:PORT
     * 此函数必须在$client->recv() 之后调用
     * @var
     */
    protected $peername;


    public function __construct(ISwooleComponent $component,array $data,ISwooleCallback $callback = null)
    {
        $this->hookup  = $component->getHookup();
        $this->component = $component;
        $this->data = $data;
        $this->callback = $callback;
    }

    public function doConnect(string $host = null, int $port = 0, float $timeout = 0.5, int $flag = 0)
    {
        //设置参数
        if(!empty($this->set))
        {
            $this->hookup->set($this->set);
        }
        /**
         * 异步建立链接设置回
         * connect会立即返回true。但实际上连接并未建立。
         * 所以不能在connect后使用send。通过isConnected()判断也是false。当连接成功后，系统会自动回调onConnect。
         * 这时才可以使用send向服务器发送数据
         */
        if($this->component->getIsSync())
        {
            //异步必须设置回调函数
            if(is_null($this->callback)){
                Read::write('Swoole UDP asynchronous link must be set callback.');
                throw new \InvalidArgumentException('Swoole UDP asynchronous link must be set callback.');
            }
            $this->hookup->on('connect',array(new $this->callback($this->data), 'onConnect'));
            $this->hookup->on('receive',array(new $this->callback($this->data), 'onReceive'));
            $this->hookup->on('close',array(new $this->callback($this->data), 'onClose'));
            $this->hookup->on('error',array(new $this->callback($this->data), 'onError'));
            return $this->hookup->connect($host??self::$host, $port == 0 ? self::$port : $port,$timeout,$flag);
        }else{
            $this->hookup->connect($host??self::$host, $port == 0 ? self::$port : $port,$timeout,$flag);
            $this->sockname = $this->hookup->getsockname();
            $this->hookup->send(json_encode($this->data));
            $res = $this->hookup->recv();
            $this->peername = $this->hookup->getpeername();
            return $res;
        }
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