<?php
class BTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group b
     */
    public function testIt()
    {
        sleep(1);
        $this->assertTrue(true);
    }
}
?>
