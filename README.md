Parallel-phpunit
================

Parallel wrapper to the [phpunit](http://www.phpunit.de/manual/3.0/en/textui.html) command
line tool. Used the same way as `phpunit`, but internally splits the execution into several
`phpunit` commands (one command per directory containing tests). Written in Bash and relies
heavily to the speed and power of the *nux command line tools. Use `parallel-phpunit` in
stead of `phpunit` if your tests take more that a minute to run.

Installation
------------

    git clone https://github.com/siivonen/parallel-phpunit.git
    export PATH=$PATH:`pwd`/parallel-phpunit/bin

Running
-------

    parallel-phpunit [phpunit switches] <directory>

Only the directory version of `phpunit` is supported so you can't replace the directory
part with file name. All given `phpunit` switches are directly passed to the parallel
`phpunit` commands.

How does it work?
-----------------

The `parallel-phpunit` command first finds out the root level test directories under given
directory. Test directories have PHPUnit tests directly inside them. Only the top level
test directories are searched. For every top level test directory following command is
executed:

    phpunit [phpunit switches] <test_dir>

Once a `phpunit` command is finnished the full output of it's execution is printed out.
When all `phpunit` commands are finnished the execution will end. The exit status is 0 if
all `phphunit` commands return 0 otherwice it is 1.

When atleast one `phpunit` command is running a total number of tests ran so far is output 
so you can see the progress. Here is an example of the output:

    Success: 30 Fail: 0 Error: 0 Skip: 3 Incomplete: 0
    Success: 35 Fail: 0 Error: 0 Skip: 3 Incomplete: 0

The total execution time of `parallel-phpunit` is the execution time of the longest lasting
`phpunit` command. You can radically improve the execution time by organizing your tests in a
directory structure where your test files are located in the leaf directories and the execution
time of every leaf directory is more or less the same. By analyzing the output of your
`parallel-phpunit` command you can determine which directories are the ones taking most of the
time and which you might want to split. There is no limit to the number of concurrent test
executions by the `parallel-phpunit`. You can run 1000 `phpunit` commands in parallel if your
server and test set can manage that. In the original use case where this script was written to
37 minute Selenium test execution was ran in 4 minutes using Selenium Grid.

Known limitations
-----------------

* Determining the parallel test directories is done using the default test file names (*Test.php 
  or *.phpt) so switch `--test-suffix` can't be used properly.

* The reporting switches (like `--coverage-*` or `--log-*`) are not guaranteed to work since all
  parallel executions are writing to the same directories or files. Only `--log-junit` is ensured 
  to work since it is handled as special case by `parallel-phphunit`.

* The test execution summary lines are counted from the "progress dots" of the `phpunit` output
  so if your tests print something between the dots you might see wrong numbers in the summary
  lines. For the same reason using `--tap` or `--testdox` will break the summary lines.
