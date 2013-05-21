<?php
class ATest extends PHPUnit_Framework_TestCase
{
    /**
     * @group a
     */
    public function testIt()
    {
        sleep(2);
        $this->assertTrue(true);
    }
}
