<?php
/**
 * ZKit Utility Tool - Log
 * @author Peter (peter.ziv@hotmail.com)
 * @date Oct 12, 2016
 * @version 1.1.1
 */

namespace ZKit\console\utility {

    /**
     *  This class is one log debugger. <p>
      $b = new LogConsole();<br>
      $b->error("console", true);<br>
      $b->info("info - console");<br>
      $b->warning("warning - console");<br>
      $c = LogConsole::getInstance("ZKit\Console\LogConsole");<br>
      $c->info("singlet - console - info");<br>

     * if you want to open the debug feature, please add <br>
     * <b>define('APP_DEBUG',true);</b><br>
     * In addtional, please notice that the timestamp depends on your time zone.
      </p>
     * @author Peter (peter.ziv@hotmail.com)

     */
    class LogConsole extends LogBasic
    {

        protected static $_instance = null;

        protected function log($type, $log)
        {
            $info = parent::log($type, $log);
            echo $info . PHP_EOL;
        }

        public static function getInstance()
        {
            if (!isset(self::$_instance)) {
                self::$_instance = new LogConsole();
            }

            return self::$_instance;
        }

    }

    /**
     * Basic class for the log
     * Notice: This is private class, it is used in code directly
     */
    class LogBasic
    {

        protected $isDateShow = true;

        public function setDateShow($isDateShow = true)
        {
            $this->isDateShow = $isDateShow;
        }

        protected function log($type, $log)
        {
            $info = "";
            if ($this->isDateShow){
                $info = date('Y-m-d H:i:s');
            }
            if (is_string($log)) {
                $info .= empty($type) ? $log : $type . ' ' . $log;
            } else {
                $log[] = $type;
                var_dump($log);
            }
            return $info;
        }

        public function write($log = '')
        {
            $this->log(null, $log);
        }

        public function info($log)
        {
            $this->log('[INFO]', $log);
        }

        public function debug($log)
        {
            if (defined('APP_DEBUG')) {
                $this->log('[DEBUG]', $log);
            }
        }

        public function error($log)
        {
            $this->log('[ERROR]', $log);
        }

        public function warning($log)
        {
            $this->log('[WARNING]', $log);
        }
    }
}