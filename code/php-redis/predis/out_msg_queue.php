<?php
/**
 * Created by PhpStorm.
 * User: PFinal南丞
 * Email: Lampxiezi@163.com
 * Date: 2020/5/25
 * Time: 9:46
 */
ini_set('default_socket_timeout', - 1);  //不超时
require './vendor/autoload.php';
$server = array(
    'host' => '127.0.0.1',
    'port' => 6379,
);
$redis = new \Predis\Client($server);
$value = $redis->lpop('mylist'); // 出队列
if ($value) {
    echo "出队的值" . $value;
} else {
    echo "出队完成";
}


