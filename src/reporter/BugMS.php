<?php
/**
 * ATUI
 * @author Peter (peter.ziv@hotmail.com)
 * @date July 15, 2017
 * @version 1.0.0
 */

namespace ZKit\ATUI {
    require_once(__DIR__ . '/utility/HttpClient.php');

    abstract class BugMS
    {

        public $url = null;
        public $domain = null;
        protected $client = null;
        protected $log = null;

        public function __construct()
        {
            $this->client = new \ZKit\console\utility\HttpClient;
            $this->log = \ZKit\console\utility\LogConsole::getInstance();
        }

        public abstract function report($data = array());
    }

}