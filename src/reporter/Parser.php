<?php
/**
 * ATUI
 * @author Peter (peter.ziv@hotmail.com)
 * @date July 15, 2017
 * @version 1.0.0
 */

namespace ZKit\ATUI {

    /**
     * Base class for result parser
     */
    abstract class Parser
    {

        private $data = array();

        /**
         * parse the result file for issues.
         * @param string $resultFile the file with path
         * @return boolean true if it success, false when failed.
         */
        public abstract function parse($resultFile);

        /**
         * get the title of this issue
         * @return string return the title of issue
         */
        public abstract function getTitle();

        /**
         * get the reproduce steps of this issue
         * @return string return the steps of issue
         */
        public abstract function getSteps();
    }

}
