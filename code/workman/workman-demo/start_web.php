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
        $connection->send(exec_php_file(WEBROOT . '/index.html'));
        return;
    }
    $file = realpath(WEBROOT . $path);
    if (false === $file) {
        $connection->send(new \Workerman\Protocols\Http\Response(404, array(), '<h3>404 Not Found</h3>'));
        return;
    }
    // Security check! Very important!!!
    if (strpos($file, WEBROOT) !== 0) {
        $connection->send(new \Workerman\Protocols\Http\Response(400));
        return;
    }
    if (\pathinfo($file, PATHINFO_EXTENSION) === 'php') {
        $connection->send(exec_php_file($file));
        return;
    }

    $if_modified_since = $request->header('if-modified-since');
    if (!empty($if_modified_since)) {
        // Check 304.
        $info = \stat($file);
        $modified_time = $info ? \date('D, d M Y H:i:s', $info['mtime']) . ' ' . \date_default_timezone_get() : '';
        if ($modified_time === $if_modified_since) {
            $connection->send(new \Workerman\Protocols\Http\Response(304));
            return;
        }
    }
    $connection->send((new \Workerman\Protocols\Http\Response())->withFile($file));
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
