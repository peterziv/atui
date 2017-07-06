<?php
/**
 * @file Log.console.php
 * This class is one log debugger.
 * @author Peter (peter.ziv@hotmail.com)

 */
//date_default_timezone_set('PRC');
//define('APP_DEBUG',true);




namespace ZKit\Console {

    /**
     *
     * <p>

      $a = new LogFile();
      $a->setFilePath(dirname(__DIR__));
      $a->setFileName("A.TXT");
      $a->error("dddddd3333333333333333333", true);
      $a->info("info test");
      $a->warning("warning test");

      $b = new LogConsole();
      $b->error("console", true);
      $b->info("info - console");
      $b->warning("warning - console");

      $c = LogConsole::getInstance("ZKit\Console\LogConsole");
      $c->info("singlet - console - info");

     * </p>
     */
    class LogConsole extends LogBasic {

        protected function log($type, $log, $isDateShow = true) {
            $info = parent::log($type, $log, $isDateShow);
            echo $info . PHP_EOL;
        }
    }

    class LogFile extends LogBasic {
        //put your code here
        private $fileDir;
        private $filename;

        public function setFilePath($path) {
            $this->fileDir = $path;
        }

        public function setFileName($fielname) {
            $this->filename = $fielname;
        }

        protected function checkPath() {
            //generate log path
            $path = $this->createPath($this->fileDir, $this->filename);
            $rs = false;
            do{
                //check if file exsiting
                if ($this->isExist($path)){
                    $rs = true;
                    break;
                }
                //no file directionary, then create directionary
                if ( !$this->createDir($this->fileDir)) {
                    break;
                }
                //create log
                $rs = $this->createLogFile($path);
            }while(false);
            return $rs?$path:false;
        }

        protected function handleLog($log) {
            $out = null;
            if (is_array($log)) {
                foreach ($log as $k => $v) {
                    $str[] = $k . " : " . $v . ";";
                }
                $str[] = PHP_EOL;
                $out = implode('', $str);
            } else {
                $out = $log.PHP_EOL;
            }
            return $out;
        }

        /**
         *Description: init and record log
         *param  log string log data string
         *param  ip string  ip string if need
         */
        public function write($log)
        {
            $rs = false;
            $path = $this->checkPath();

            do{
                if($path === false){
                    $console = new LogConsole();
                    $console->error("Error path for the log file!");
                    break;
                }
                $filehandler = fopen($path, "a+");

                if (fwrite($filehandler, $this->handleLog($log))) {
                    $rs = true;
                }
                //close file handler
                fclose($filehandler);
            }while(false);
            return $rs;
        }

        /**
         *check if existing path
         *param  path string  log path
         *return: true | false
         */
        private function isExist($path)
        {
            return file_exists($path);
        }

        /**
         * create path
         * @param string $dir The directory to create.
         * @return bool result to create directory.
         */
        private function createDir($dir)
        {
            if(empty($dir)){
                return false;
            }
            return is_dir($dir) or ($this->createDir(dirname($dir)) and mkdir($dir, 0777));
        }

        /**
         *Create the log
         *param  path string  log path
         *return: true | false
         */
        private function createLogFile($path)
        {
            $handle = fopen($path, "w");
            fclose($handle);
            return $this->isExist($path);
        }

        /**
         *create the file path
         *param  dir      string  log path
         *param  filename string  log name
         *return: path string
         */
        private function createPath($dir, $filename){
            if (empty($dir)) {
                return $filename;
            } else {
                return $dir . "/" . $filename;
            }
        }

        protected function log($type, $log, $isDateShow = true) {
            $info = parent::log($type, $log, $isDateShow);
            $this->write($info);
        }

        public function __construct() {
            $this->init();
        }

        protected function init(){
            $this->filename = date('Ymdhis', time()) . '.log';
            $this->fileDir = __DIR__;
        }
    }

    /**
     * Basic class for the log
     * Notice: This is private class, it is used in code directly
     */
    class LogBasic{
        protected static $_instance = null;
        protected $isDateShow = true;


        public function setDateShow($isDateShow = true){
            $this->isDateShow = $isDateShow;
        }

        public static function getInstance($calledClass = '') {
            if (!isset(self::$_instance)) {
                self::$_instance = new $calledClass;
            }

            return self::$_instance;
        }

        protected function log($type, $log) {
            $info = "";
            if ($this->isDateShow){
                $info = date('Y-m-d h:i:s');
            }
            $info .= $type . ' ' . $log;
            return $info;
        }

        public function info($log)
        {
            $this->log('[INFO]', $log);
        }

        public function debug($log) {
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