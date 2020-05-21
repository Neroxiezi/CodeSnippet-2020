<?php
/**
 * Created by PhpStorm.
 * User: PFinal南丞
 * Email: Lampxiezi@163.com
 * Date: 2020/5/21
 * Time: 16:50
 */
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$strKey = 'Test_bihu_score';

// 存储数据
$redis->zadd($strKey, '50', json_encode(['name' => 'Tom']));
$redis->zadd($strKey, '70', json_encode(['name' => 'John']));
$redis->zadd($strKey, '90', json_encode(['name' => 'Jerry']));
$redis->zadd($strKey, '30', json_encode(['name' => 'Job']));
$redis->zadd($strKey, '100', json_encode(['name' => 'LiMing']));

$dataOne = $redis->zRevRange($strKey,0,-1,true);
echo "---- {$strKey}由大到小的排序 ---- \n";
print_r($dataOne);

$dataTwo = $redis->zRange($strKey,0,-1,true);
echo "---- {$strKey}由小到大的排序 ---- \n";
print_r($dataTwo);
