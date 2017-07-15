<?php

/**
 * ATUI
 * @author Peter (peter.ziv@hotmail.com)
 * @date July 15, 2017
 * @version 1.0.0
 */

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

        /**
         * initialize the tracker based on configuration file.
         * @param array $data
         * @return boolean return the state to initialize bug tracker.
         */
        private function initTracker($data = array())
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

        private function initBug($data = array())
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