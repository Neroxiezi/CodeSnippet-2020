<?php
/**
 * Created by PhpStorm.
 * User: PFinal南丞
 * Email: Lampxiezi@163.com
 * Date: 2020/5/26
 * Time: 10:44
 */
//实例化
$redis = new Redis();
//连接服务器
$redis_connect = $redis->connect("localhost", 6379);

$arr = [
    '2020-05-10' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
    '2020-05-11' => [1, 2, 3, 4, 5, 6, 7, 8],
    '2020-05-12' => [1, 2, 3, 4, 5, 6],
    '2020-05-13' => [1, 2, 3, 4],
    '2020-05-14' => [1, 2]
];
// 设置活跃用户
foreach ($arr as $date => $uids) {
    $cacheKey = sprintf("stat_%s", $date);
    foreach ($uids as $uid) {
        $redis->setBit($cacheKey, $uid, 1);
    }
}

$redis->bitOp('AND', 'stat', 'stat_2020-05-10', 'stat_2020-05-11', 'stat_2020-05-12') . PHP_EOL;
//总活跃用户：6
echo "总活跃用户：" . $redis->bitCount('stat') . PHP_EOL;

$redis->bitOp('OR','stat1','stat_2020-05-10','stat_2020-05-14');
echo $redis->bitCount('stat1'). PHP_EOL;;
$redis->bitOp('XOR','stat2','stat_2020-05-10','stat_2020-05-14');
echo $redis->bitCount('stat2'). PHP_EOL;;

$redis->close();
