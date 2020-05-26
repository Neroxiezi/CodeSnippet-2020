<?php
/**
 * Created by PhpStorm.
 * User: PFinal南丞
 * Email: Lampxiezi@163.com
 * Date: 2020/5/26
 * Time: 10:36
 */

//实例化
$redis = new Redis();
//连接服务器
$redis_connect = $redis->connect("localhost", 6379);
$uid = 1;
$cacheKey = sprintf("sign_%d", $uid);
$startDate = '2020-05-01';
$todayDate = '2020-05-26';
// 计算offset
$startTime = strtotime($startDate);
$todayTime = strtotime($todayDate);
$offset = floor(($todayTime - $startTime) / 86400);
echo "今天是第{$offset}天" . PHP_EOL;
//签到
$redis->setBit($cacheKey, $offset, 1);
$bitStatus = $redis->getBit($cacheKey, $offset);
echo 1 == $bitStatus ? '今天已经签到啦' : '还没有签到呢';
echo PHP_EOL;
echo $redis->bitCount($cacheKey) . PHP_EOL;

$redis->close();
