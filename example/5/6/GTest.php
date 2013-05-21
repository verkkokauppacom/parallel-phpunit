<?php
class GTest extends PHPUnit_Framework_TestCase
{
    public function testIt()
    {
        sleep(2);
        $this->assertTrue(true);
    }
}
