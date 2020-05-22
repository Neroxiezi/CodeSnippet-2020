<?php
/**
 * Created by PhpStorm.
 * User: PFinal南丞
 * Email: Lampxiezi@163.com
 * Date: 2020/5/22
 * Time: 14:35
 */
//实例化
$redis = new Redis();
//连接服务器
$redis_connect = $redis->connect("localhost", 6379);

$rkey = 'Set_key';
$rkey_two = 'Set_key_two';
$redis->sAdd($rkey, 'Pfinal');
$redis->sAdd($rkey, 'Pfinal南丞');
$redis->sRem($rkey, 'Pfinal'); // 删除集合中的元素
var_dump($redis->sMembers($rkey)); // 获取集合所有元素
echo "\n";
$redis->sMove($rkey,$rkey_two,'Pfinal南丞');
var_dump($redis->sMembers($rkey_two));
echo "\n";
var_dump($redis->sIsMember($rkey_two,'PFinal')); //检测集合总是否有元素
var_dump($redis->sIsMember($rkey_two,'Pfinal南丞'));
var_dump($redis->sCard($rkey_two)); //返回SET容器的成员数
var_dump($redis->sCard($rkey)); //返回SET容器的成员数
$redis->close();


