<?php
namespace Client;

use Swoole\ISwooleCallback;
use Swoole\ISwooleComponent;
use Swoole\SwooleDecorator;

class TcpClient extends SwooleDecorator {

    //swoole_client
    private  $hookup;

    //BaseSwooleConnect
    private $component;

    //传输的数据
    private $data = [];

    //回调类
    private $callback;

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
                throw new \InvalidArgumentException('Swoole TCP/UDP asynchronous link must be set callback.');
            }
            $this->hookup->on('connect',array(new $this->callback($this->data), 'onConnect'));
            $this->hookup->on('receive',array(new $this->callback($this->data), 'onReceive'));
            $this->hookup->on('close',array(new $this->callback($this->data), 'onClose'));
            $this->hookup->on('error',array(new $this->callback($this->data), 'onError'));
            //回调设置好之后在链接服务器
            return $this->hookup->connect($host??self::$host, $port == 0 ? self::$port : $port,$timeout,$flag);
        }else{
            //建立链接 同步阻塞
            if ($this->hookup->connect($host??self::$host, $port == 0 ? self::$port : $port,$timeout,$flag)) {
                echo "swoole_client Asynchronous link successfully.\n";
                $this->hookup->send(json_encode($this->data));
                $res = $this->hookup->recv();
                return $res;
            } else {
                //同步阻塞 长连接模式重连
                if($this->component->getIsKeep())
                {
                    //失败重连 5 次
                    $i = 1;
                    while ($i <= 5){
                        echo sprintf( "Client reconnection...%d",$i).PHP_EOL;
                        sleep(2);
                        //启用SWOOLE_KEEP长连接后，close调用的第一个参数要设置为true表示强行销毁长连接socket
                        $this->hookup->close(true);
                        if($this->hookup->connect($host??self::$host, $port == 0 ? self::$port : $port,$timeout,$flag)){
                            echo "swoole_client Asynchronous link successfully.\n";
                            $this->peername =  $this->hookup->getpeername();
                            $this->hookup->send(json_encode($this->data));
                            $res = $this->hookup->recv();
                            return $res;
                        };
                        $i++;
                    }
                }
                echo "TCP long link failure.".PHP_EOL;
                return false;
            }
        }
    }

    /**
     * 用于获取客户端socket的本地host:port
     * 如：array('host' => '127.0.0.1', 'port' => 53652)
     * @return mixed
     */
    public function getPeerName()
    {
        return $this->peername;
    }

    public function setOptions(array $option = [])
    {
        return $this->set = $option;
    }
}