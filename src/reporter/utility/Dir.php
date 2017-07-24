<?php
/**
 * ZKit Utility Tool - Log
 * @author Peter (peter.ziv@hotmail.com)
 * @date Oct 12, 2016
 * @version 1.1.1
 */

namespace ZKit\console\utility {
    require_once __DIR__ . '/Log.php';

    /**
     * It is to find the sub directionary and file under one specified path.
     * @author Peter (peter.ziv@hotmail.com)
     *
     */
    class Dir {
        public $rootPath = null;
        protected $log = null;
        protected $initState = true;

        /**
         * New the class with parmeter: $root
         * @param string $root
         */
        public function __construct()
        {
            $this->log = LogConsole::getInstance();
            $this->initState = $this->init();
        }

        /**
         * One interface for init something before starting to find objects
         * @return boolean it will stop folder finding if false returned.
         */
        protected function init()
        {
            $this->log->debug('init function');
            return true;
        }

        /**
         * Find the object ( file/directory) under a folder.
         * @param string $shortPath 当前文件夹名，为空从设定根目录查找
         */
        public function findUnderFolder($shortPath = '')
        {
            if ($this->initState) {
                $realPath = $this->rootPath . $shortPath;
                $this->log->debug('find Under Folder:' . $realPath);

                foreach (glob($realPath . '/*') as $single) {
                    $objectName = substr_replace($single, '', 0, strlen($realPath) + 1);
                    if (is_dir($single)) {
                        $this->doWhenFoundDirectory($shortPath, $objectName);
                    } else {
                        $this->doWhenFoundFile($shortPath, $objectName);
                    }
                }
            }
        }

        /**
         * Need to rewrite this function.
         * @param string $prefixPath 路径前缀
         * @param string $dirName  文件夹名
         */
        public function doWhenFoundDirectory($prefixPath, $dirName)
        {
            $this->log->debug('Found a directory ' . $prefixPath . "/" . $dirName);
        }

        /**
         * Need to rewrite this function.
         * @param string $prefixPath 路径前缀
         * @param string $fileName 文件夹名
         */
        public function doWhenFoundFile($prefixPath, $fileName)
        {
            $this->log->debug('Found a file: ' . $prefixPath . "/" . $fileName);
        }
    }

    class DirTraversal extends Dir{
        public function doWhenFoundDirectory($preFixPath, $dirName)
        {
            $dir = $preFixPath . '/' . $dirName;
            $this->log->debug("Try to search in " . $dir );
            $this->findUnderFolder($dir);
        }

    }
}
