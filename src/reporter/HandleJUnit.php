<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of HandleJUnit
 *
 * @author admin
 */
namespace ZKit\ATUI {
    require_once __DIR__ . '/basic/Dir.console.php';
    require_once __DIR__ . '/Loader.php';
    require_once __DIR__ . '/JUnitParser.php';
    require_once __DIR__ . '/Zentao.php';

    class HandleJUnit extends \ZKit\Console\Dir
    {

        private $loader = array();

        public function __construct()
        {
            $this->log = new \ZKit\Console\LogConsole;
            $this->initState = $this->init();
        }

        protected function init()
        {
            $res = true;
            $this->loader = new Loader();
            if (!$this->loader->init()) {
                $this->log->error('Failed to init the bug tracker!');
                $res = false;
            } else {
                $this->rootPath = $this->loader->junit;
            }
            return $res;
        }

        public function doWhenFoundFile($preFixPath, $fileName)
        {
            $fileType = substr($fileName, -4);
            if ('.xml' === $fileType) {
                $this->report($this->rootPath . $preFixPath . '/' . $fileName);
            }
        }

        private function report($file)
        {
            $p = new JUnitParser;
            $rs = $p->check($file);
            if (!is_null($rs)) {
                $result = $this->loader->bug;
                $result['title'] = $rs;
                $this->bug($result);
            }
        }

        private function bug($data)
        {
            $bms = $this->loader->tracker;
            $bms->report($data);
        }

    }

}
