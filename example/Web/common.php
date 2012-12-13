<?php
function debugStart($className) {
    writeLog('Start: ' . $className . ': ' . date('H:i:s'));
}


function debugEnd($className) {
    writeLog('End: ' . $className . ': ' . date('H:i:s'));
}


function writeLog($msg) {
    return ;
    file_put_contents(
        '/tmp/debug.log', date('Y-m-d H:i:s') . ': ' . $msg . "\n", FILE_APPEND
    );
}


function debugSleep($className)
{

    $tm = 2;
    switch ($className) {
        case 'Web_Config3Test':
            $tm = 1;
            break;
        default:
            PHPUnit_Framework_Assert::assertTrue(false, 'error test here');
    }

    sleep($tm);
}
