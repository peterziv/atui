<?php

/**
 * ATUI
 * @author Peter (peter.ziv@hotmail.com)
 * @date July 15, 2017
 * @version 1.0.0
 */

require_once(__DIR__ . '/HandleJUnit.php');

//define('APP_DEBUG', true);
$p = new \ZKit\ATUI\HandleJUnit();
$p->findUnderFolder();
