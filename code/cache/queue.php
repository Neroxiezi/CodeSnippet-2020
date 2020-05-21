<?php
/**
 * Created by PhpStorm.
 * User: PFinal南丞
 * Email: Lampxiezi@163.com
 * Date: 2020/5/21
 * Time: 16:10
 */
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$strQueueName = 'Test_bihu_queue';
echo '<pre>';
//进队列
$redis->rPush($strQueueName,json_encode(['uid'=>1,'name'=>'Job']));
$redis->rPush($strQueueName,json_encode(['uid'=>2,'name'=>'Tom']));
$redis->rPush($strQueueName,json_encode(['uid'=>3,'name'=>'John']));
echo '------------入队---------<br><br>';

// 查看队列
$strCount = $redis->lRange($strQueueName,0,-1);
echo "当前队列数据为： <br />";
echo '<pre>';
print_r($strCount);

// 出队列
$redis->lPop($strQueueName);
echo "<br /><br /> ---- 出队列成功 ---- <br /><br />";

$strCount = $redis->lRange($strQueueName,0,-1);
echo "当前队列数据为： <br />";
print_r($strCount);
