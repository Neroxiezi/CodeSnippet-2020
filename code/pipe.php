<?php
$pipe = './test.pipe';

if (!file_exists($pipe)) {
    if (!posix_mkfifo($pipe, 0666)) {
        exit('创建命名管道失败' . PHP_EOL);
    }
}

// 创建子进程, 子进程写管道, 父进程读管道
$pid = pcntl_fork();

switch ($pid) {
    case -1:
        die('fork failed');
        break;
    case 0:
        $file = fopen($pipe, 'w');
        fwrite($file, 'hello world' );
        sleep(3);
        exit;
        break;
    default:
        $file = fopen($pipe, 'r');
        // 父进程阻塞, 直到子进程退出
        echo fread($file, 20) . PHP_EOL;
        // 父进程阻塞, 直到子进程退出
        pcntl_wait($status);
        break;
}