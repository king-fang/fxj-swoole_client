<?php
namespace Swoole;

class Read {

    public static function write($errorLog)
    {
        date_default_timezone_set('PRC');
        if(!file_exists(__DIR__."/swoole.log"))
        {
            fopen(__DIR__."/swoole.log", "w");
        }
        swoole_async_writefile(__DIR__."/swoole.log",$errorLog."[".date('Y-m-d H:i:s')."]".PHP_EOL,function () use ($errorLog){
        },FILE_APPEND);
    }
}