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

    class HandleJUnit extends \ZKit\console\utility\Dir
    {

        private $loader = array();

        protected function init()
        {
            $res = false;

            do {
                if (!$this->extensionRequired()) {
                    break;
                }

                $this->loader = new Loader();
                if (!$this->loader->init()) {
                    $this->log->error('Failed to init the bug tracker!');
                } else {
                    $this->rootPath = $this->loader->junit;
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
            $rs = $p->parse($file);
            if (array_key_exists('msg', $rs)) {
                $result = $this->loader->bug;

                $result['title'] = $rs['type'] . ':' . $rs['class'] . '.' . $rs['function'] . '-' . $rs['msg'];
                $this->log->info($result['title']);
                if (array_key_exists('steps', $rs)) {
                    $result['steps'] = $rs['steps'];
                    $this->log->info($result['steps']);
                }
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
