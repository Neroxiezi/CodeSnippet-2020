<?php
/**
 * Created by PhpStorm.
 * User: PFinal南丞
 * Email: Lampxiezi@163.com
 * Date: 2020/5/21
 * Time: 15:54
 */
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$strCacheKey = 'Test_bihu';

//SET 应用
$arrCacheData = [
    'name' => 'job',
    'sex' => '男',
    'age' => '30'
];
$redis->set($strCacheKey, json_encode($arrCacheData));
$redis->expire($strCacheKey, 30); #设置30秒后过期
$json_data = $redis->get($strCacheKey);
$data = json_decode($json_data);
print_r($data->age); //输出数据

//HSET 应用
$arrWebSite = [
    'google' => [
        'google.com',
        'google.com.hk'
    ],
];
$redis->hSet($strCacheKey, 'google', json_encode($arrWebSite['google']));
$json_data = $redis->hGet($strCacheKey, 'google');
$data = json_decode($json_data);
var_dump($data); //输出数据


















