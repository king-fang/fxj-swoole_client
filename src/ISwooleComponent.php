<?php


abstract class ISwooleComponent {

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
    abstract static protected function doConnect(string $host, int $port, float $timeout = 0.5, int $flag = 0);

}