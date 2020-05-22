<?php

/**
 * Created by PhpStorm.
 * User: PFinal南丞
 * Email: Lampxiezi@163.com
 * Date: 2020/5/21
 * Time: 17:36
 */
class RpcClient
{
    protected $client = null;
    protected $url_info = [];   // 远程调用 URL 组成部分
    public function __construct($url)
    {
        $this->url_info = parse_url($url);
    }

    public static function instance($url)
    {
        return new RpcClient($url);
    }

    public function check_server()
    {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        @$ok = socket_connect($socket, $this->url_info['host'], $this->url_info['port']);
        if ($ok) {
            return true;
        } else {
            throw new ErrorException('服务没有开启');
        }
    }


    public function __call($name, $arguments)
    {
        // 创建一个客户端
        $this->client = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (!$this->client) {
            exit('socket_create() 失败');
        }
        socket_connect($this->client, $this->url_info['host'], $this->url_info['port']);
        // 传递调用的类名
        $class = basename($this->url_info['path']);
        // 传递调用的参数
        $args = '';
        if (isset($arguments[0])) {
            $args = json_encode($arguments[0]);
        }
        // 向服务端发送我们自定义的协议数据
        $proto = "Rpc-Class: {$class};" . PHP_EOL
            . "Rpc-Method: {$name};" . PHP_EOL
            . "Rpc-Params: {$args};" . PHP_EOL;
        socket_write($this->client, $proto);
        // 读取服务端传来的数据
        $buf = socket_read($this->client, 8024);
        socket_close($this->client);

        return $buf;
    }
}
