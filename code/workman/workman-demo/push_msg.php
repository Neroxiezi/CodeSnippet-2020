<?php
/**
 * Created by PhpStorm.
 * User: PFinal南丞
 * Email: Lampxiezi@163.com
 * Date: 2020/5/25
 * Time: 17:58
 */

// 指明给谁推送，为空表示向所有在线用户推送
$to_uid = '1590400927000';
// 推送的url地址，上线时改成自己的服务器地址
$push_api_url = "http://localhost:2121/";
$post_data = array(
    'type' => 'publish',
    'content' => '这个是推送的测试数据',
    'to' => $to_uid,
);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $push_api_url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
$return = curl_exec($ch);
curl_close($ch);
var_export($return);
