<?php
namespace ZKit\ATUI {

    class Loader
    {

        public $bug = null;
        public $tracker = null;
        public $junit = '.';

        public function init()
        {
            $rs = false;
            do {
                $string = file_get_contents('config.json');
                $data = json_decode($string, true);
                if (is_null($data)) {
                    break;
                }
                if (!$this->initTracker($data['tracker'])) {
                    break;
                }
                $this->initJUnitReport($data['junit']);
                $rs = true;
            }while (false);
            return $rs;
        }

        public function initTracker($data = array())
        {
            $rs = false;
            if ('ZenTao' == $data['name']) {
                $this->tracker = new Zentao;
                $this->tracker->domain = $data['domain'];
                $this->tracker->user = $data['user'];
                $this->tracker->pwd = $data['password'];
                $rs = true;
            }
            $this->initBug($data);
            return $rs;
        }

        private function initBug($data)
        {
            if (array_key_exists('bug', $data)) {
                $this->bug = $data['bug'];
            }
        }

        private function initJUnitReport($path)
        {
            $this->junit = $path;
        }

    }

}