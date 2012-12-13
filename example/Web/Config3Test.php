<?php
require_once(__DIR__ . '/common.php');

class Web_Config3Test extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        debugStart(__CLASS__);
    }


    public function tearDown()
    {
        debugEnd(__CLASS__);
    }


    public function testOne() {
        debugSleep(__CLASS__);
        $this->assertTrue(true, "False is not true");
    }

}
