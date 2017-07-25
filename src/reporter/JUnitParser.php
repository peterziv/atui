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

        /**
         * parse the result file for issues.
         * @param string $resultFile the file with path
         * @return array the result parsed<p>
         * The format is:<br/>
         * [
         *  'type'=>$type,
         *  'class'=>$class,
         *  'function'=>$function,
         *  'msg'=>$msg,
         *  'steps'=>$steps
         * ]<br/>
         * <b>$type</b> is 'failure' or 'error'<br/>
         * <b>$msg</b> is brief of issue<br/>
         * <b>$steps</b> is detail of issue description<br/>
         * </p>
         */
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

        private function findDescription($reader, &$data)
        {
            if ($reader->read()) {
                if ($reader->nodeType === \XMLReader::CDATA || $reader->nodeType === \XMLReader::TEXT) {
                    $data['steps'] = $reader->value;
                }
            }
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
                case 'error':
                case 'failure':
                    if (REPORT_PARSER_TESTCASE_FOUND === $step) {
                        $data['type'] = $reader->name;
                        $data['msg'] = $this->handleMsg($reader->getAttribute('message'), strlen($data['type']) + strlen($data['class']) + strlen($data['function']));
                        $this->findDescription($reader, $data);
                        $step = REPORT_PARSER_DONE;
                    }
                    break;
            }
        }

        private function handleMsg($message, $lenCost)
        {
            $log = \ZKit\console\utility\LogConsole::getInstance();
            $log->debug('original message: ' . $message);
            $pos = strpos($message, "\n");
            if (false === $pos) {
                $pos = 250;
                $log->warning('NOT find &#10;');
            }
            $log->debug('position---->' . $pos);
            $log->debug('total message: ' . $message);
            return substr($message, 0, $pos > 250 - $lenCost ? (250 - $lenCost) : $pos);
        }

    }
}
