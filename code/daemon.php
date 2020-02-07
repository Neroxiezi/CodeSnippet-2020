<?php

class Daemon
{
    const DLOG_TO_CONSOLE = 1;
    const DLOG_NOTICE = 2;
    const DLOG_WARNING = 4;
    const DLOG_ERROR = 8;
    const DLOG_CRITICAL = 16;

    const DAPC_PATH = '/tmp/daemon_apc_keys';
    public $userID = 65534; // nobody

    /**
     * Group ID
     *
     * @var integer
     */
    public $groupID = 65533; // nobody

    /**
     * Terminate daemon when set identity failure ?
     *
     * @var bool
     * @since 1.0.3
     */
    public $requireSetIdentity = false;

    /**
     * Path to PID file
     *
     * @var string
     * @since 1.0.1
     */
    public $pidFileLocation = '/tmp/daemon.pid';

    /**
     * processLocation
     * 进程信息记录目录
     *
     * @var string
     */
    public $processLocation = '';

    /**
     * processHeartLocation
     * 进程心跳包文件
     *
     * @var string
     */
    public $processHeartLocation = '';

    /**
     * Home path
     *
     * @var string
     * @since 1.0
     */
    public $homePath = '/';

    /**
     * Current process ID
     *
     * @var int
     * @since 1.0
     */
    protected $_pid = 0;

    /**
     * Is this process a children
     *
     * @var boolean
     * @since 1.0
     */
    protected $_isChildren = false;

    /**
     * Is daemon running
     *
     * @var boolean
     * @since 1.0
     */
    protected $_isRunning = false;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {

        error_reporting(0);
        set_time_limit(0);
        ob_implicit_flush();

        register_shutdown_function(array(&$this, 'releaseDaemon'));
    }

    /**
     * 启动进程
     *
     * @return bool
     */
    public function main()
    {

        $this->_logMessage('Starting daemon');

        if (!$this->_daemonize()) {
            $this->_logMessage('Could not start daemon', self::DLOG_ERROR);

            return false;
        }

        $this->_logMessage('Running...');

        $this->_isRunning = true;

        while ($this->_isRunning) {
            $this->_doTask();
        }

        return true;
    }

    /**
     * 停止进程
     *
     * @return void
     */
    public function stop()
    {

        $this->_logMessage('Stoping daemon');

        $this->_isRunning = false;
    }

    /**
     * Do task
     *
     * @return void
     */
    protected function _doTask()
    {
        // override this method
    }

    /**
     * _logMessage
     * 记录日志
     *
     * @param string 消息
     * @param integer 级别
     * @return void
     */
    protected function _logMessage($msg, $level = self::DLOG_NOTICE)
    {
        // override this method
    }

    /**
     * Daemonize
     *
     * Several rules or characteristics that most daemons possess:
     * 1) Check is daemon already running
     * 2) Fork child process
     * 3) Sets identity
     * 4) Make current process a session laeder
     * 5) Write process ID to file
     * 6) Change home path
     * 7) umask(0)
     *
     * @access private
     * @return void
     * @since 1.0
     */
    private function _daemonize()
    {

        ob_end_flush();

        if ($this->_isDaemonRunning()) {
            // Deamon is already running. Exiting
            return false;
        }

        if (!$this->_fork()) {
            // Coudn't fork. Exiting.
            return false;
        }

        if (!$this->_setIdentity() && $this->requireSetIdentity) {
            // Required identity set failed. Exiting
            return false;
        }

        if (!posix_setsid()) {
            $this->_logMessage('Could not make the current process a session leader', self::DLOG_ERROR);

            return false;
        }

        if (!$fp = fopen($this->pidFileLocation, 'w')) {
            $this->_logMessage('Could not write to PID file', self::DLOG_ERROR);
            return false;
        } else {
            fputs($fp, $this->_pid);
            fclose($fp);
        }

        // 写入监控日志
        $this->writeProcess();

        chdir($this->homePath);
        umask(0);

        declare(ticks=1);

        pcntl_signal(SIGCHLD, array(&$this, 'sigHandler'));
        pcntl_signal(SIGTERM, array(&$this, 'sigHandler'));
        pcntl_signal(SIGUSR1, array(&$this, 'sigHandler'));
        pcntl_signal(SIGUSR2, array(&$this, 'sigHandler'));

        return true;
    }

    /**
     * Cheks is daemon already running
     *
     * @return bool
     */
    private function _isDaemonRunning()
    {

        $oldPid = file_get_contents($this->pidFileLocation);

        if ($oldPid !== false && posix_kill(trim($oldPid), 0)) {
            $this->_logMessage('Daemon already running with PID: ' . $oldPid, (self::DLOG_TO_CONSOLE | self::DLOG_ERROR));

            return true;
        } else {
            return false;
        }
    }

    /**
     * Forks process
     *
     * @return bool
     */
    private function _fork()
    {

        $this->_logMessage('Forking...');

        $pid = pcntl_fork();

        if ($pid == -1) {
            // 出错
            $this->_logMessage('Could not fork', self::DLOG_ERROR);

            return false;
        } elseif ($pid) {
            // 父进程
            $this->_logMessage('Killing parent');

            exit();
        } else {
            // fork的子进程
            $this->_isChildren = true;
            $this->_pid = posix_getpid();

            return true;
        }
    }

    /**
     * Sets identity of a daemon and returns result
     *
     * @return bool
     */
    private function _setIdentity()
    {

        if (!posix_setgid($this->groupID) || !posix_setuid($this->userID)) {
            $this->_logMessage('Could not set identity', self::DLOG_WARNING);

            return false;
        } else {
            return true;
        }
    }

    /**
     * Signals handler
     *
     * @access public
     * @return void
     * @since 1.0
     */
    public function sigHandler($sigNo)
    {

        switch ($sigNo) {
            case SIGTERM:   // Shutdown
                $this->_logMessage('Shutdown signal');
                exit();
                break;

            case SIGCHLD:   // Halt
                $this->_logMessage('Halt signal');
                while (pcntl_waitpid(-1, $status, WNOHANG) > 0) ;
                break;
            case SIGUSR1:   // User-defined
                $this->_logMessage('User-defined signal 1');
                $this->_sigHandlerUser1();
                break;
            case SIGUSR2:   // User-defined
                $this->_logMessage('User-defined signal 2');
                $this->_sigHandlerUser2();
                break;
        }
    }

    /**
     * Signals handler: USR1
     *  主要用于定时清理每个进程里被缓存的域名dns解析记录
     *
     * @return void
     */
    protected function _sigHandlerUser1()
    {
        apc_clear_cache('user');
    }

    /**
     * Signals handler: USR2
     * 用于写入心跳包文件
     *
     * @return void
     */
    protected function _sigHandlerUser2()
    {

        $this->_initProcessLocation();

        file_put_contents($this->processHeartLocation, time());

        return true;
    }

    /**
     * Releases daemon pid file
     * This method is called on exit (destructor like)
     *
     * @return void
     */
    public function releaseDaemon()
    {

        if ($this->_isChildren && is_file($this->pidFileLocation)) {
            $this->_logMessage('Releasing daemon');

            unlink($this->pidFileLocation);
        }
    }

    /**
     * writeProcess
     * 将当前进程信息写入监控日志，另外的脚本会扫描监控日志的数据发送信号，如果没有响应则重启进程
     *
     * @return void
     */
    public function writeProcess()
    {

        // 初始化 proc
        $this->_initProcessLocation();

        $command = trim(implode(' ', $_SERVER['argv']));

        // 指定进程的目录
        $processDir = $this->processLocation . '/' . $this->_pid;
        $processCmdFile = $processDir . '/cmd';
        $processPwdFile = $processDir . '/pwd';

        // 所有进程所在的目录
        if (!is_dir($this->processLocation)) {
            mkdir($this->processLocation, 0777);
            chmod($processDir, 0777);
        }

        // 查询重复的进程记录
        $pDirObject = dir($this->processLocation);
        while ($pDirObject && (($pid = $pDirObject->read()) !== false)) {
            if ($pid == '.' || $pid == '..' || intval($pid) != $pid) {
                continue;
            }

            $pDir = $this->processLocation . '/' . $pid;
            $pCmdFile = $pDir . '/cmd';
            $pPwdFile = $pDir . '/pwd';
            $pHeartFile = $pDir . '/heart';

            // 根据cmd检查启动相同参数的进程
            if (is_file($pCmdFile) && trim(file_get_contents($pCmdFile)) == $command) {
                unlink($pCmdFile);
                unlink($pPwdFile);
                unlink($pHeartFile);

                // 删目录有缓存
                usleep(1000);

                rmdir($pDir);
            }
        }

        // 新进程目录
        if (!is_dir($processDir)) {
            mkdir($processDir, 0777);
            chmod($processDir, 0777);
        }

        // 写入命令参数
        file_put_contents($processCmdFile, $command);
        file_put_contents($processPwdFile, $_SERVER['PWD']);

        // 写文件有缓存
        usleep(1000);

        return true;
    }

    /**
     * _initProcessLocation
     * 初始化
     *
     * @return void
     */
    protected function _initProcessLocation()
    {

        $this->processLocation = ROOT_PATH . '/app/data/proc';
        $this->processHeartLocation = $this->processLocation . '/' . $this->_pid . '/heart';
    }
}