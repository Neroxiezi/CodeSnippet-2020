<?php
require 'Component/RpcClient.php';
var_dump(123);
switch (@$_GET['method']) {
    case 'check_status':
        $cli = new RpcClient('http://127.0.0.1:8888/test');
        var_dump($cli);
        break;
}
