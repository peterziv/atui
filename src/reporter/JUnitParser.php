<?php

/**
 * ATUI
 * @author Peter (peter.ziv@hotmail.com)
 * @date July 15, 2017
 * @version 1.0.0
 */

namespace ZKit\ATUI {
    require_once __DIR__ . '/utility/Log.php';

    define('REPORT_PARSER_START', 0);
    define('REPORT_PARSER_TESTCASE_FOUND', 1);
    define('REPORT_PARSER_DONE', 2);

    /**
     * It is one log parser for JUnit format report
     */
    class JUnitParser
    {

        public function parse($resultFile)
        {
            $log = \ZKit\console\utility\LogConsole::getInstance();
            $log->debug('Found a file: ' . $resultFile);

            $data = array();
            $reader = new \XMLReader();
            $reader->open($resultFile, 'UTF-8');
            $step = REPORT_PARSER_START;
            while ($reader->read()) {
                if ($reader->nodeType !== \XMLReader::ELEMENT) {
                    continue;
                }

                $this->findIssue($reader, $data, $step);
                if (REPORT_PARSER_DONE === $step) {
                    $log->info('Found an issue in ' . $resultFile);
                    break;
                }
            }
            $reader->close();
            return $data;
        }

        /**
         * find issue taged as failure and error under parent tag testcase
         * @param XMLReader $reader
         * @param array $data the data of the issue
         * @param int $step the step to lookup issue.
         */
        private function findIssue($reader, &$data, &$step)
        {
            switch ($reader->name) {
                case 'testcase':
                    $step = REPORT_PARSER_TESTCASE_FOUND;
                    $data['class'] = $reader->getAttribute('classname');
                    $data['function'] = $reader->getAttribute('name');
                    break;
                case 'failure':
                case 'error':
                    if (REPORT_PARSER_TESTCASE_FOUND === $step) {
                        $msg = $reader->getAttribute('message');
                        $pos = strpos($msg, '(Session');
                        $data['msg'] = (false === $pos ? $msg : preg_replace("/([\s]{2,})/", '', substr($msg, 0, $pos)));

                        $step = REPORT_PARSER_DONE;
                    }
                    break;
            }
        }

    }
}
