<?php

/**
 * ATUI
 * @author Peter (peter.ziv@hotmail.com)
 * @date July 15, 2017
 * @version 1.0.0
 */

namespace ZKit\ATUI {
    require_once __DIR__ . '/utility/Log.php';

    /**
     * Description of HandleJUnit
     *
     * @author admin
     */
    class JUnitParser
    {

        public function check($resultFile)
        {
            $log = new \ZKit\console\utility\LogConsole;
            $log->debug('Found a file: ' . $resultFile);

            $reader = new \XMLReader();
            $reader->open($resultFile, 'UTF-8');
            $data = null;
            while ($reader->read()) {
                if ($reader->name != "testcase" || $reader->nodeType != \XMLReader::ELEMENT) {
                    continue;
                }
                $function = $reader->getAttribute('name');
                $className = $reader->getAttribute('classname');
                while ($reader->read()) {
                    if ($reader->name == "failure" && $reader->nodeType == \XMLReader::ELEMENT) {
//                        $type = $reader->getAttribute('type');
                        $data = $className . '.' . $function . '-' . $reader->getAttribute('message');
                        $log->info($data);
                    }
                }
            }
            $reader->close();
            return $data;
        }

    }
}
