<?php
/**
 * Created by PhpStorm.
 * User: PFinal南丞
 * Email: Lampxiezi@163.com
 * Date: 2020/5/22
 * Time: 15:18
 */
//redis hash是一个string类型的field和value的映射表.它的添加，删除操作都是O(1)（平均）.hash特别适合用于存储对象。
//实例化
$redis = new Redis();
//连接服务器
$redis_connect = $redis->connect("localhost", 6379);
$key = 'Test_hash';
$redis->hSet($key,'name','pfinal南丞');
$res = $redis->hSetNx($key,'sex',1); //添加sex字段 value为1  如果字段sex的value存在返回false 否则返回 true
print_r($res);
$redis->hSet($key,'email','Lampxiezi@163.com');
print_r($redis->hGetAll($key));
print_r($redis->hGet($key,'name'));
print_r($redis->hLen($key));
print_r($redis->hDel($key,'email'));
print_r($redis->hGetAll($key));
$redis->hSet($key, 'age', 28);
echo "\n";
print_r($redis->hKeys($key));
$redis->hIncrBy($key,'age',1);
echo "\n";
print_r($redis->hVals($key));
echo "\n";
$redis->hMSet($key,['source'=>30,'salary'=>2000]);
print_r($redis->hGetAll($key));
echo "\n";
print_r($redis->hMGet($key,$redis->hKeys($key)));
$redis->close();
