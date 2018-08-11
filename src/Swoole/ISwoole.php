<?php
namespace Swoole;
interface ISwoole {

    const HOST = '127.0.0.1';

    const POST = '9501';

    const SERVER_TYPE = ['tcp' => SWOOLE_SOCK_TCP,'udp' => SWOOLE_SOCK_UDP];

    /**
     * 设置客户端参数，必须在connect前执行
     * 选项url地址:https://wiki.swoole.com/wiki/page/p-client_setting.html
     * @param array $option
     * @return mixed
     */
    public function setOptions(array $option = []);
}