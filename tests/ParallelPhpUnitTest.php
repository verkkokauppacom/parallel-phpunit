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
    --pu-retries - how many times to rerun the test file if it fails
Usage: parallel-phpunit [switches] <directory>
EOS;
        $commandOutput = $this->runParallelPHPUnit("", 1);
        $this->assertEquals($helpOutput, $commandOutput);
    }

    public function testRetries()
    {
        $arguments = " --test-suffix FailEverySecondTime.php " . __DIR__;
        file_put_contents("/tmp/failTheTest", "");
        $this->runParallelPHPUnit($arguments, 1);
        file_put_contents("/tmp/failTheTest", "");
        $this->runParallelPHPUnit("--pu-retries 1" . $arguments, 0);
    }

    private function runParallelPHPUnit($arguments, $expectedExitStatus = 0)
    {
        $command = __DIR__ . "/../bin/parallel-phpunit " . $arguments;
        $output = array();
        $exitStatus = -1;
        exec($command, $output, $exitStatus);
        $this->assertEquals($expectedExitStatus, $exitStatus);
        return implode($output, "\n");
    }
}
?>
