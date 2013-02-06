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
long time before we have it. If we have it at all. `parallel-phpunit` is already working
solution that you can just start using.

When?
-----

When your `phpunit` command takes too long (in your opinion) to execute you should test if
`parallel-phpunit` makes it faster.

What do I need?
---------------

`parallel-phpunit` is written in Bash and relies heavily to the speed and power of the 
*nux command line tools. To run it you need to have:

* Working Bash environment (tested to work at least in Linux and Mac)
* Working `phpunit` command

How to install?
---------------

To install `parallel-phpunit` you just need to add the 
[bin](https://github.com/siivonen/parallel-phpunit/tree/master/bin) directory to 
your PATH:

    cd /path/of/your/choice
    git clone https://github.com/siivonen/parallel-phpunit.git
    <add /path/of/your/choice/parallel-phpunit/bin to your PATH>

To choose which version you want to use (or to upgrade or downgrade) you just use
the corresponding release branch:

    git fetch
    git checkout -t origin/release/1.1
    
How to run?
-----------

The usage is:

    parallel-phpunit [phpunit and parallel-phpunit switches] <directory>

Only the directory version of `phpunit` is supported so you can't replace the directory
part with file name. The parallel-phpunit switches are:
 * --pu-cmd - Custom phpunit run script, default value is 'phpunit'
 * --pu-threads - The maximum number of parallel phpunit commands running at the same time, default value is '3'

All other switches are considered to be `phpunit` switches and they are directly passed to the 
parallel `phpunit` commands.

How does it work?
-----------------

The `parallel-phpunit` command first finds all phpunit test files under given directory. By default
all file names ending with 'Test.php' or '.phpt' are considered to be test files. You can change this
default with phpunit switch --test-suffix. Test files are executed in alphabetical order and for every 
test file following command is executed:

    phpunit [phpunit switches] <test_file>

There is a maximum limit of parallel phpunit commands (controlled by switch --pu-threads) and only this
amount of concurrent test executions are running at the same time. The rest of the executions are waiting
for some running test execution to finnish.

When atleast one `phpunit` command is running a summary report line is printed once every second.
Here is an example output:

    Success: 30 Fail: 0 Error: 0 Skip: 3 Incomplete: 0
    Success: 35 Fail: 0 Error: 0 Skip: 3 Incomplete: 0

Once a `phpunit` command is finnished the full output of it's execution is printed out.
When all `phpunit` commands are finnished the execution will end. The exit status is 0 if
all `phphunit` commands return 0 otherwice it is 1.

There is a simple example test set that you can use to test or study `parallel-phpunit`. Run
following command in the root of your parallel-phpunit Git clone:

    parallel-phpunit example

This will run a simple test set parallelizing it into three concurrent `phpunit` commands. You
can use this command to test that your `parallel-phpunit` command works. You can also investigate 
and maybe change the tests and directory sctructure under 
[example](https://github.com/siivonen/parallel-phpunit/tree/master/example) to learn how the 
`parallel-phpunit` command works.

Known limitations
-----------------

* The reporting switches (like `--coverage-*` or `--log-*`) are not guaranteed to work since all
  parallel executions are writing to the same directories or files. Only `--log-junit` is ensured 
  to work since it is handled as special case by `parallel-phphunit`.

* The test execution summary lines are counted from the "progress dots" of the `phpunit` output
  so if your tests print something between the dots you might see wrong numbers in the summary
  lines. For the same reason using `--tap` or `--testdox` will break the summary lines.

Release Notes
-------------

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


