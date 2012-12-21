<?php
class ParallelPhpUnitTest extends PHPUnit_Framework_TestCase
{
    public function testHelpMessage()
    {
        $helpOutput = <<<EOS
Running parallel wrapper for phpunit
Paralleling options:
    --pu-cmd - custom phpunit run script, default phpunit
    --pu-thread - max threads, default 3
Usage: parallel-phpunit [switches] <directory>
EOS;
        $this->verifyCommandOutput("", 1, $helpOutput);
    }

    private function verifyCommandOutput($arguments, $expectedExitStatus = 0, $expectedOutput = null)
    {
        $parallel_phpunit = __DIR__ . "/../bin/parallel-phpunit";
        $output = array();
        $exitStatus = -1;
        exec($parallel_phpunit, $output, $exitStatus);
        $this->assertEquals($expectedExitStatus, $exitStatus);
        $expectedOutput && $this->assertEquals($expectedOutput, implode("\n", $output));
    }
}
?>
