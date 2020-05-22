<?php
/**
 * Created by PhpStorm.
 * User: PFinal南丞
 * Email: Lampxiezi@163.com
 * Date: 2020/5/22
 * Time: 13:32
 */
//实例化
$redis = new Redis();
//连接服务器
$redis_connect = $redis->connect("localhost", 6379);
//$strCacheKey = 'Test_bihu';
//
//$redis->set($strCacheKey, 'TK');
//
//$str = $redis->get($strCacheKey);
//print_r($str);
$strCacheKey1 = 'Test_string';
//$redis->setex($strCacheKey1,5,'PFinal社区');
//$str = $redis->get($strCacheKey1);
//print_r($str);

$str = $redis->getSet($strCacheKey1,'PFinal');
var_dump($str);
