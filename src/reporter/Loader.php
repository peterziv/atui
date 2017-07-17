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
                if (false === $string) {
                    break;
                }
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
            do {
                if (!array_key_exists('name', $data)) {
                    break;
                }

                if ($this->newTracker($data['name'])) {
                    $this->tracker->domain = $data['domain'];
                    $this->tracker->user = $data['user'];
                    $this->tracker->pwd = $data['password'];
                    $rs = $this->initBug($data);
                }
            } while (false);
            return $rs;
        }

        /**
         * new one tracker based on its name
         * @param string $name tracker name
         * @return boolean it is true if it is supported, others are false.
         */
        private function newTracker($name)
        {
            $rs = true;
            switch ($name) {
                case 'ZenTao':
                    $this->tracker = new Zentao;
                    break;
                default:
                    $rs = false;
                    break;
            }
            return $rs;
        }

        private function initBug($data = array())
        {
            $rs = false;
            if (array_key_exists('bug', $data)) {
                $this->bug = $data['bug'];
                $rs = true;
            }
            return $rs;
        }

        private function initJUnitReport($path)
        {
            $this->junit = $path;
        }

    }

}