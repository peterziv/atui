<?php
namespace ZKit\ATUI {
    require_once(__DIR__ . '/basic/HttpClient.php');

    abstract class BugMS
    {

        public $url = null;
        public $domain = null;
        protected $client = null;

        public function __construct()
        {
            $this->client = new \ZKit\Console\HttpClient;
        }

        public abstract function report($data = array());
    }

}