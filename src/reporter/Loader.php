<?php

/**
 * ATUI
 * @author Peter (peter.ziv@hotmail.com)
 * @date July 15, 2017
 * @version 1.0.0
 */

namespace ZKit\ATUI {

    /**
     * This class is to load the configuration.
     */
    class Loader
    {

        public $bug = null;
        public $tracker = null;
        public $junit = '.';
        private $log = null;

        public function init()
        {
            $rs = false;
            do {
                $this->log = new \ZKit\console\utility\LogConsole();
                $string = $this->openConf();
                if (false === $string) {
                    break;
                }
                $data = json_decode($string, true);
                if (is_null($data)) {
                    $this->log->error('Error configuration file format.');
                    break;
                }
                if (!$this->initTracker($data['tracker'])) {
                    $this->log->error('Failed to init the bug tracker!');
                    break;
                }
                $this->initJUnitReport($data['junit']);
                $rs = true;
            }while (false);
            return $rs;
        }

        /**
         * get configuration file path
         * @return boolean|string it will return the configuration file path string, false when it is not existing
         */
        private function getConf()
        {
            $conf = getcwd() . DIRECTORY_SEPARATOR . 'config.json';
            if (!file_exists($conf)) {
                $conf = getenv('USERPROFILE') . DIRECTORY_SEPARATOR . $conf;
            }
            if (!file_exists($conf)) {
                $this->log->error('Please check the configuration file: ' . $conf);
                return false;
            }

            return $conf;
        }

        /**
         * get content of configuration file
         * @return boolean|string it will return the configuration file content string, false when it is failed to get the content.
         */
        private function openConf()
        {
            $rs = false;
            do {
                $conf = $this->getConf();
                if (false === $conf) {
                    break;
                }
                $rs = file_get_contents($conf);
                if (false === $rs) {
                    $this->log->error('Error configuration file: ' . $conf);
                    break;
                }
            } while (false);
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