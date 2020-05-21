<?php
/**
 * Created by PhpStorm.
 * User: PFinal南丞
 * Email: Lampxiezi@163.com
 * Date: 2020/5/21
 * Time: 16:26
 */
ini_set('default_socket_timeout', - 1);
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$strChannel = 'Test_bihu_channel';

//发布
$redis->publish($strChannel,"来自{$strChannel}频道的消息推送");
echo "---- {$strChannel} ---- 频道消息推送成功～ <br/>";
$redis->close();
