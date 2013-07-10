<?php
class ParallelPhpUnitTest extends PHPUnit_Framework_TestCase
{
    public function testHelpMessage()
    {
        $helpOutput = <<<EOS
Running parallel-phpunit 1.3.0
Paralleling options:
    --pu-cmd - custom phpunit run script, default: first phpunit in PATH or phpunit next to parallel-phpunit
    --pu-threads - max threads, default 3
    --pu-retries - how many times to rerun the test file if it fails
    --pu-verbose - print all phpunit commands and their output, otherwise only failing commands are written
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

    public function testFiltering()
    {
        $emptyOutput = "Running parallel-phpunit 1.3.0\nSuccess: 0 Fail: 0 Error: 0 Skip: 0 Incomplete: 0";
        $testDir = __DIR__ . "/../example";
        $output = $this->runParallelPHPUnit("--filter noTestsFound " . $testDir, 0);
        $this->assertEquals($emptyOutput, $output);
        $output = $this->runParallelPHPUnit("--filter ATest::testIt " . $testDir, 0);
        $this->assertEquals("Success: 1 Fail: 0 Error: 0 Skip: 0 Incomplete: 0", end(explode("\n", $output)));
        $this->assertFalse(strstr($output, "No tests"));
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
