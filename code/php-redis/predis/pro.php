<?php
/**
 * Created by PhpStorm.
 * User: PFinal南丞
 * Email: Lampxiezi@163.com
 * Date: 2020/5/22
 * Time: 16:02
 */
ini_set('default_socket_timeout', - 1);  //不超时
require './vendor/autoload.php';
$server = array(
    'host' => '127.0.0.1',
    'port' => 6379,
);
$redis = new \Predis\Client($server);
$key = 'SMS_QUEUE';

for ($i = 0; $i < 10; $i ++) {
    // 获取随机手机号 // 有的时候不同用户文本内容不一样 最好可以分开设置。注意key名可以根据情况更改
    $json = json_encode(["mobile" => randomPhoneNumber(), "msg" => "【滴滴答】尊敬的用户您好！祝您新年快乐！"]);
    // 左入队列
    $redis->lPush($key, $json);
    echo 'yes:' . $i . PHP_EOL;
}


function randomPhoneNumber()
{
    // 手机号头
    $header = ["133", "149", "153", "173", "177",
        "180", "181", "189", "199", "130", "131", "132",
        "145", "155", "156", "166", "171", "175", "176",
        "185", "186", "166", "134", "135", "136", "137",
        "138", "139", "147", "150", "151", "152", "157",
        "158", "159", "172", "178", "182", "183", "184",
        "187", "188", "198", "170", "171"];
    $count        = count($header);
    $header_value = rand(0, $count - 1);
    return $header[$header_value] . '****' . rand(1000, 9999);
}
