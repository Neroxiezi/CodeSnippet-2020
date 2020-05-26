<?php
/**
 * Created by PhpStorm.
 * User: PFinal南丞
 * Email: Lampxiezi@163.com
 * Date: 2020/5/26
 * Time: 10:25
 */
//实例化
$redis = new Redis();

//连接服务器
$redis_connect = $redis->connect("localhost", 6379);
$lockKey = 'lock:18806767777&37781991111629092&taoshihan';
$resultKey = 'res:18806767777&37781991111629092&taoshihan';

$info = $redis->get($resultKey);
if ($info) {
    exit($info);
}

// 如果没有值的 获取锁
$lock = $redis->set($lockKey, 1, ['nx', 'ex' => 10]);
if ($lock) {
    //请求外部系统获取结果,比如响应结果比较慢
    sleep(8);
    $info = '{"name":"taoshihan"}';
    $ret = $redis->set($resultKey, $info);
    if ($ret) {
        //删除锁
        $redis->del($lockKey);
        exit($info);
    }
}
echo "请稍后重试！";

$redis->close();
