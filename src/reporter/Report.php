<?php

/**
 * ATUI
 * @author Peter (peter.ziv@hotmail.com)
 * @license https://github.com/peterziv/atui/blob/master/LICENSE Apache License 2.0
 * @date July 15, 2017
 * @version 1.0.0
 */

require_once(__DIR__ . '/IssueHandler.php');

//define('APP_DEBUG', true);
define('REPORTER_VERSION', '1.0-alpha3');

$log = new \ZKit\console\utility\LogConsole();
$log->setDateShow(false);
$log->println();
$log->println('ATUI - Reporter ' . REPORTER_VERSION);
$log->println('# Auto report the issue to the tracker');
$log->println('# Author: peter<peter.ziv@hotmail.com> ');
$log->println();
$log->setDateShow(true);

$p = new \ZKit\ATUI\IssueHandler();
$p->findUnderFolder();
if ($p->getIusseCount() > 0) {
    $log->info('Totally reported ' . $p->getIusseCount() . ' issues!');
} else {
    $log->info('No issue found!');
}
$log->setDateShow(false);
$log->println();
