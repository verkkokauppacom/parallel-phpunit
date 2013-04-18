<?php
class ParallelPhpUnitTest extends PHPUnit_Framework_TestCase
{
    public function testHelpMessage()
    {
        $helpOutput = <<<EOS
Running parallel-phpunit 1.2
Paralleling options:
    --pu-cmd - custom phpunit run script, default phpunit
    --pu-threads - max threads, default 3
Usage: parallel-phpunit [switches] <directory>
EOS;
        $this->runParallelPHPUnit("", 1, $helpOutput);
    }

    private function runParallelPHPUnit($arguments, $expectedExitStatus = 0, $expectedOutput = null)
    {
        $command = __DIR__ . "/../bin/parallel-phpunit " . $arguments;
        $output = array();
        $exitStatus = -1;
        exec($command, $output, $exitStatus);
        $this->assertEquals($expectedExitStatus, $exitStatus);
        $expectedOutput && $this->assertEquals($expectedOutput, implode("\n", $output));
    }
}
?>
