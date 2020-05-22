<?php
require 'Component/RpcClient.php';
switch (@$_GET['method']) {
    case 'check_status':
        try {
            $rpcClient = new RpcClient('http://127.0.0.1:8080/test');
            $rpcClient->check_server();
            echo $rpcClient->get_html($_GET['article']);
        } catch (\ErrorException $e) {
            //var_dump($e->getMessage());
            echo $e->getMessage();
        }
        break;
}
