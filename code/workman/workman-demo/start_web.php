<?php
/**
 * Created by PhpStorm.
 * User: PFinal南丞
 * Email: Lampxiezi@163.com
 * Date: 2020/5/25
 * Time: 16:43
 */
require __DIR__ . '/vendor/autoload.php';
$web = new \Workerman\Worker('http://0.0.0.0:2123');
$web->name = 'web';
define('WEBROOT', __DIR__ . DIRECTORY_SEPARATOR . 'web');

$web->onMessage = function (\Workerman\Connection\TcpConnection $connection, \Workerman\Protocols\Http\Request $request) {
    $path = $request->path();
    if ($path === "/") {
        $connection->send(exec_php_file(WEBROOT.'/index.html'));
        return;
    }
};

function exec_php_file($file)
{
    \ob_start();
    // Try to include php file.
    try {
        include $file;
    } catch (\Exception $e) {
        echo $e;
    }
    return \ob_get_clean();
}

if (!defined('GLOBAL_START')) {
    \Workerman\Worker::runAll();
}
