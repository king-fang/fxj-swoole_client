<?php
namespace Swoole;

/**
 * 异步TCP/UDP 回调
 * Interface ISwooleCallback
 * @package Swoole
 */
interface ISwooleCallback {

    public function onConnect($cli);

    public function onReceive($cli, $data);

    public function onClose($cli);

    public function onError($cli);
}