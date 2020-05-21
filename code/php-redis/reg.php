<?php
/**
 * Created by PhpStorm.
 * User: PFinal南丞
 * Email: Lampxiezi@163.com
 * Date: 2020/5/21
 * Time: 13:32
 */
require 'redis.php';
$name = $_POST['name'];
$pass = md5($_POST['pass']);
$age = $_POST['age'];
