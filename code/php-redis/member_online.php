<?php
/**
 * Created by PhpStorm.
 * User: PFinal南丞
 * Email: Lampxiezi@163.com
 * Date: 2020/5/26
 * Time: 11:00
 */
//实例化
$redis = new Redis();
//连接服务器
$redis_connect = $redis->connect("localhost", 6379);
$uids = range(1, 500000);
foreach ($uids as $uid) {
    $redis->setBit('online', $uid, $uid % 2);
}

//一个一个获取状态
$uids = range(1, 500000);
$startTime = microtime(true);
foreach ($uids as $uid) {
    echo $redis->getBit('online', $uid) . PHP_EOL;
}
$endTime = microtime(true);
//在我的电脑上，获取50W个用户的状态需要25秒
echo "total:" . ($endTime - $startTime) . "s";
$redis->close();
