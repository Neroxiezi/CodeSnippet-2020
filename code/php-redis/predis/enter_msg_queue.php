<?php
/**
 * Created by PhpStorm.
 * User: PFinal南丞
 * Email: Lampxiezi@163.com
 * Date: 2020/5/25
 * Time: 9:40
 */
ini_set('default_socket_timeout', - 1);  //不超时
require './vendor/autoload.php';
$server = array(
    'host' => '127.0.0.1',
    'port' => 6379,
);
$redis = new \Predis\Client($server);
$arr = ['h', 'e', 'l', 'l', 'o', 'p', 'f', 'i', 'n', 'a', 'l'];
foreach ($arr as $item) {
    $redis->rpush('mylist', $item);
}

