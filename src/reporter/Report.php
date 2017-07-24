<?php

/**
 * ATUI
 * @author Peter (peter.ziv@hotmail.com)
 * @date July 15, 2017
 * @version 1.0.0
 */

require_once(__DIR__ . '/HandleJUnit.php');
define('REPORTER_VERSION', '1.0-alpha.2');

$log = new \ZKit\console\utility\LogConsole();
$log->setDateShow(false);
$log->println();
$log->println('ATUI - Reporter ' . REPORTER_VERSION);
$log->println('# Auto report the issue to the tracker');
$log->println('# Author: peter<peter.ziv@hotmail.com> ');
$log->println();
$log->setDateShow(true);
//define('APP_DEBUG', true);
$p = new \ZKit\ATUI\HandleJUnit();
$p->findUnderFolder();
$log->info('Totally reported ' . $p->getIusseCount() . ' issue(s)!');
