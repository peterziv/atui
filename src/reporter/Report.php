<?php

require_once(__DIR__ . '/HandleJUnit.php');

//define('APP_DEBUG', true);
$p = new \ZKit\ATUI\HandleJUnit();
$p->findUnderFolder();
