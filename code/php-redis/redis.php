<?php
/**
 * Created by PhpStorm.
 * User: PFinal南丞
 * Email: Lampxiezi@163.com
 * Date: 2020/5/21
 * Time: 13:26
 */
//实例化
$redis = new Redis();
//连接服务器
$redis_connect = $redis->connect("localhost", 6379);
var_dump($redis_connect);
