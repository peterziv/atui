<?php

/**
 * ATUI
 * @author Peter (peter.ziv@hotmail.com)
 * @date July 15, 2017
 * @version 1.0.0
 */
namespace ZKit\ATUI {
    require_once __DIR__ . '/utility/Dir.php';
    require_once __DIR__ . '/Loader.php';
    require_once __DIR__ . '/JUnitParser.php';
    require_once __DIR__ . '/Zentao.php';

    class IssueHandler extends \ZKit\console\utility\Dir
    {

        private $loader = null;
        private $issueCnt = 0;

        protected function init()
        {
            $res = false;

            do {
                if (!$this->extensionRequired()) {
                    break;
                }

                $this->loader = new Loader();
                if ($this->loader->init()) {
                    $this->rootPath = $this->loader->junit;
                    $this->log->info('Report according to result in ' . $this->rootPath);
                    $res = true;
                }
            } while (false);
            return $res;
        }

        private function extensionRequired()
        {
            $rs = true;
            $list = array('curl');
            foreach ($list as $ext) {
                if (!extension_loaded($ext)) {
                    $this->log->error('php extension ' . $ext . ' is not load!');
                    $rs = false;
                    break;
                }
            }
            return $rs;
        }

        /**
         * get the count number of issue
         * @return int return the count number of issue
         */
        public function getIusseCount()
        {
            return $this->issueCnt;
        }

        /**
         * The action to handle files
         * @param string $prefixPath The prefix path
         * @param string $fileName  The file name found
         */
        public function doWhenFoundFile($prefixPath, $fileName)
        {
            $fileType = substr($fileName, -4);
            if ('.xml' === $fileType) {
                $this->log->info('Try to finger out issue by ' . $fileName);
                $this->report($this->rootPath . $prefixPath . '/' . $fileName);
            }
        }

        private function report($file)
        {
            $p = new JUnitParser;
            if ($p->parse($file)) {
                $result = $this->loader->bug;

                $result['title'] = $p->getTitle();
                $this->log->info($result['title']);
                $result['steps'] = $p->getSteps();
                $this->log->debug($result['steps']);
                $this->bug($result);
            }
        }

        private function bug($data)
        {
            $bms = $this->loader->tracker;
            $bms->report($data);
            $this->issueCnt++;
        }

    }

}
