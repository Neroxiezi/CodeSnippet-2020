<?php
/**
 * Created by PhpStorm.
 * User: PFinal南丞
 * Email: Lampxiezi@163.com
 * Date: 2020/5/22
 * Time: 16:09
 */
ini_set('default_socket_timeout', - 1);  //不超时
require './vendor/autoload.php';
$server = array(
    'host' => '127.0.0.1',
    'port' => 6379,
);
$redis = new \Predis\Client($server);
$key = 'SMS_QUEUE';
while ($key_arr = $redis->brpop($key, 0)) {
    try {
        // key_arr 是一个数组 0 表示key名称 SMS_QUEUE ,1 表示获取到的值
        usleep(100000);
        $json = $key_arr[1];
        //解析json
        $json_arr = json_decode($json, true);
        if (is_array($json_arr) && count($json_arr) == 2) {
            if (!sms($json_arr["mobile"], $json_arr["msg"])) {
                throw new Exception("发送失败");
            }
        } else {
            throw new Exception("解析失败");
        }
    } catch (Exception $e) {
        // 左入队列
        $redis->lPush($key, $json);
    }
}

function sms($mobile, $msg)
{
    echo '用户手机号：' . $mobile . '，信息：' . $msg . ' 发送成功！' . PHP_EOL;
    return true;
}
