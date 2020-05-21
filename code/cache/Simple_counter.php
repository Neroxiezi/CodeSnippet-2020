<?php
/**
 * Created by PhpStorm.
 * User: PFinal南丞
 * Email: Lampxiezi@163.com
 * Date: 2020/5/21
 * Time: 16:40
 */
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$strKey = 'Test_bihu_comments';

// 设置初始值
$redis->set($strKey, 0);

$redis->INCR($strKey);
$redis->INCR($strKey);
$redis->INCR($strKey);

$strNowCount = $redis->get($strKey);
echo "---- 当前数量为{$strNowCount}。 ---- ";
