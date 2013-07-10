What?
-----

`phpunit` is a command line tool for running 
[PHPUnit](https://github.com/sebastianbergmann/phpunit/) tests. It does not support 
running tests in parallel. `parallel-phpunit` is a command line tool that works the 
same way as `phpunit` but runs the tests in parallel. It searches for PHPUnit tests that
you want to execute, starts multiple `phpunit` commands at the same time to run them, 
monitors and reports the progress and returns the results at the end. The user experience
of `parallel-phpunit` is the same as `phpunit`. In most use cases you can just simply 
replace `phpunit` with `parallel-phpunit` and get the same end result.

Why?
----

Because it's faster! You can save a lot of time in your development or continuous 
integration simply by repacing `phpunit` with `parallel-phpunit`. Often you have tests that 
are valuable but they just take time to execute. Selenium tests for example are slow by 
nature and the more you have them the longer your test execution takes.

The built-in parallel support for PHPUnit has been wanted and waited for a long time but 
nothing has happened. Considering the PHP language level restrictions like poor thread 
support and the global nature of built-in things (like code coverage) it could take a
long time before we have it. If we have it at all. `parallel-phpunit` is already a working
solution that you can just start using.

When?
-----

When your `phpunit` command takes too long (in your opinion) to execute you should test if
`parallel-phpunit` makes it faster.

What do I need?
---------------

`parallel-phpunit` is written in Bash and relies heavily to the speed and power of the 
*nux command line tools. To run it you need to have:

* Working Bash environment (tested to work in Mac and several flavors of Linux)
* Working `phpunit` command

How to install?
---------------

To install `parallel-phpunit` you just need to add the 
[bin](https://github.com/verkkokauppacom/parallel-phpunit/tree/master/bin) directory to 
your PATH:

    cd /path/of/your/choice
    git clone https://github.com/verkkokauppacom/parallel-phpunit.git
    <add /path/of/your/choice/parallel-phpunit/bin to your PATH>

To choose which version you want to use (or to upgrade or downgrade) you just use
the corresponding release branch:

    git fetch
    git checkout 1.3.0
   
Alternatively you can also install `parallel-phpunit` with Composer: (https://packagist.org/packages/verkkokauppacom/parallel-phpunit).

How to run?
-----------

The usage is:

    parallel-phpunit [phpunit and parallel-phpunit switches] <directory>

Only the directory version of `phpunit` is supported so you can't replace the directory
part with file name. The parallel-phpunit switches are:
 * --pu-cmd - Custom phpunit run script (default: first phpunit in PATH or phpunit next to parallel-phpunit)
 * --pu-threads - The maximum number of parallel `phpunit` commands running at the same time (3 by default)
 * --pu-retries - How many times to rerun the test file if it fails (0 by default)
 * --pu-verbose - Print all starting and ending phpunit commands and their outputs, by default only failing cases are output (off by default)

All other switches are considered to be `phpunit` switches and they are directly passed to the 
`phpunit` commands.

How does it work?
-----------------

The `parallel-phpunit` command first finds all phpunit test files under given directory. By default
all file names ending with 'Test.php' or '.phpt' are considered to be test files. You can change this
default with `phpunit` switch --test-suffix. The test files are then filtered to those that match your
`phpunit` --filter switch. If you haven't given any filter in command line no filtering will be done
to the file list. Test files are executed in alphabetical order and for every test file following command 
is executed:

    phpunit [phpunit switches] <test_file>

There is a maximum limit of parallel phpunit commands (controlled by switch --pu-threads) and only this
amount of concurrent test executions are running at the same time. The rest of the executions are waiting
for some running test execution to finish.

When atleast one `phpunit` command is running a summary report line is printed once every second.
Here is an example output:

    Success: 30 Fail: 0 Error: 0 Skip: 3 Incomplete: 0
    Success: 35 Fail: 0 Error: 0 Skip: 3 Incomplete: 0

If any `phpunit` command fails the command and it's output is printed out otherwise the execution is 
silent. You can change this default behavior by adding switch --pu-verbose to your command. Then all 
the individual `phpunit` commands and their outputs are printed out regardless of their outcome. If
your tests are unstable (sometimes failing when they should succeed) you can add switch --pu-retries
to your command. This will cause `parallel-phpunit` to rerun (for maximum given number of times) failing 
`phpunit` commands to verify that they are truly broken. If a failing command succeeds in some retry
it is considered to be successful. When all `phpunit` commands are finnished the execution will end.
The exit status is 0 if all `phphunit` commands return 0 otherwice it is 1.

There is a simple example test set that you can use to test or study `parallel-phpunit`. Run
following command in the root of your parallel-phpunit Git clone:

    parallel-phpunit example

This will run a simple test set parallelizing it into three concurrent `phpunit` commands. You
can use this command to test that your `parallel-phpunit` command works. You can also investigate 
and maybe change the tests and directory sctructure under 
[example](https://github.com/verkkokauppacom/parallel-phpunit/tree/master/example) to learn how the 
`parallel-phpunit` command works.

Known limitations
-----------------

* The reporting switches (like `--coverage-*` or `--log-*`) are not guaranteed to work since all
  parallel executions are writing to the same directories or files. Only `--log-junit` is ensured 
  to work since it is handled as special case by `parallel-phphunit`.

* The test execution summary lines are counted from the "progress dots" of the `phpunit` output
  so if your tests print something between the dots you might see wrong numbers in the summary
  lines. For the same reason using `--tap` or `--testdox` will break the summary lines.

* Filtering the test file list is based on running the filter regular expression on the whole file content not 
  just the Classname::testMethod string as done in PHPUnit. That's why sometimes test file list is not filtered
  correctly (out commented test methods will be matched for example and some corner case regular expressions,
  like 'ClassName..testMethod', will not work).

Release Notes
-------------

Master

Release 1.3.0
* Consider failing phpunit commands as test failures (fix for issue #13)
* Add support for Composer installation
* Filter the file list to the ones that match given --filter switch (no more "No tests executed" in the logs)
* Add --pu-retries switch (handy for unstable Selenium test for example)

Release 1.2
* Move repository from siivonen to verkkokauppacom
* Fix for issue 4 (temporary log files left hanging in some environments)
* Use separate directory to write temporary files (parallel-phpunit* files no longer written next to tests)

Relese 1.1
* Start using parallelization by test file instead of parallelization by test directory
* Switch --pu-threads to control the number of cuncurrent phpunit commands
* Switch --pu-cmd to overwrite phpunit command line to be used in parallel executions
* Allow changing the test file name pattern (=use --test-suffix instead of hard coded pattern)

Release 1.0
* The first fully working version


Contributing and error reporting
--------------------------------

Use the standard GitHub tools: pull requests, issues and Wiki.


