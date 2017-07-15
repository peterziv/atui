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
     * It is one log parser for JUnit format report
     */
    class JUnitParser
    {

        public function parse($resultFile)
        {
            $log = \ZKit\console\utility\LogConsole::getInstance();
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
