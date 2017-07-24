<?php

/**
 * ATUI - tests
 * This is the scripts to test reporter::Loader
 * @author peter<peter.ziv@hotmail.com>
 */

use PHPUnit\Framework\TestCase;

require_once __DIR__.'/../../src/reporter/Loader.php';

class LoaderTest extends TestCase
{

    public function testInit()
    {

        $filename = getenv('USERPROFILE') . DIRECTORY_SEPARATOR . 'config.json';
        $rs = true;
        if (file_exists($filename)) {
            $rs = unlink($filename);
        }
        if ($rs) {
            $loader = new \ZKit\ATUI\Loader();
            $this->assertEquals(false, $loader->init());

            copy(__DIR__ . '/../../deploy/config.json', $filename);
            $this->assertEquals(true, $loader->init());

            $this->assertEquals(array('product' => 1, 'openedBuild' => 'trunk', 'type' => 'automation', 'severity' => 3), $loader->bug);
            $this->assertEquals('admin', $loader->tracker->user);
            $this->assertEquals('ZenTaoPMS', $loader->tracker->pwd);
            $this->assertEquals('http://127.0.0.1/pms', $loader->tracker->domain);
            $this->assertEquals('target/surefire-reports', $loader->junit);
        }
    }

}