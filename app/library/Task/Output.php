<?php
namespace Task;

use Phalcon\Logger\Formatter\Line as LineFormatter;
use Phalcon\Di;
class Output
{

    /**
     * Bash shell code for red
     * @const COLOR_RED
     */
    const COLOR_RED = "\e[0;31m";

    /**
     * Bash shell code for yellow
     * @const COLOR_YELLOW
     */
    const COLOR_YELLOW = "\e[1;33m";

    /**
     * Bash shell code for green
     * @const COLOR_GREEN
     */
    const COLOR_GREEN = "\e[0;32m";

    /**
     * Bash shell code for blue
     * @const COLOR_BLUE
     */
    const COLOR_BLUE = "\e[0;34m";

    /**
     * Bash shell code for no color
     * @const COLOR_NONE original shell color
     */
    const COLOR_NONE = "\e[0m";

    /**
     *
     * @var string output sent to standard error
     */
    protected static $_stderr;

    /**
     *
     * @var string output sent to standard output
     */
    protected static $_stdout;

    /**
     * output to standard error
     *
     * @param $msg string
     */
    public static function stderr($msg)
    {
        $now = date("H:i:s");
        if (Process::$daemon) {
            $logger = DI::getDefault() ->get('logger');
            $formatter = new LineFormatter("{$now} -> %message%");
            $logger->setFormatter($formatter);
            $logger->error($msg);
        } else {
            fwrite(STDERR, self::COLOR_RED.$now . ' -> ' . $msg . PHP_EOL.self::COLOR_RED);
            self::$_stderr .= $msg . PHP_EOL;
        }
    }

    /**
     * output to standard output
     *
     * @param $msg string
     */
    public static function stdout($msg)
    {
        $now = date("H:i:s");
        if (Process::$daemon) {
            $logger = DI::getDefault() ->get('logger');
            $formatter = new LineFormatter("{$now} -> %message%");
            $logger->setFormatter($formatter);
            $logger->info($msg);
        } else {
            fwrite(STDOUT, self::COLOR_GREEN .$now . ' -> ' . $msg . PHP_EOL.self::COLOR_GREEN);
            self::$_stdout .= $msg . PHP_EOL;
        }
    }

    /**
     * get all standard output text
     *
     * @return string
     */
    public static function getStdout()
    {
        return self::$_stdout;
    }

    /**
     * get all standard error text
     *
     * @return string
     */
    public static function getStderr()
    {
        return self::$_stderr;
    }

    /**
     * Clear Output
     */
    public static function clear()
    {
        unset(self::$_stderr);
        self::$_stderr = '';
        unset(self::$_stdout);
        self::$_stdout = '';
    }
}
