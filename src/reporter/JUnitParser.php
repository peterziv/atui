<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


namespace ZKit\ATUI {
    require_once __DIR__ . '/basic/Log.console.php';

    /**
     * Description of HandleJUnit
     *
     * @author admin
     */
    class JUnitParser
    {

        public function check($resultFile)
        {
            $log = new \ZKit\Console\LogConsole;
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
