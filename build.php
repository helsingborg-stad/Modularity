#!/bin/php
<?php
// Only allow run from cli.
if (php_sapi_name() !== 'cli') {
    exit(0);
}

// Any command needed to run and build plugin assets when newly cheched out of repo.
$buildCommands = [
    'npm ci --no-progress --no-audit',
    'npx browserslist@latest --update-db',
    'npm run build',
    'composer install --prefer-dist --no-progress'
];

// Files and directories not suitable for prod to be removed.
$removables = [
    '.git',
    '.gitignore',
    '.github',
    'build.php',
    'composer.json',
    'composer.lock',
    'node_modules',
    'package.json',
    'package-lock.json',
    '.vscode',
    'modularity-custom-module-example'
];

$dirName = basename(dirname(__FILE__));

// Run all build commands.
$output = '';
$exitCode = 0;
foreach ($buildCommands as $buildCommand) {
    print "---- Running build command '$buildCommand' for $dirName. ----\n";
    $timeStart = microtime(true);
    $exitCode = executeCommand($buildCommand);
    $buildTime = round(microtime(true) - $timeStart);
    print "---- Done build command '$buildCommand' for $dirName.  Build time: $buildTime seconds. ----\n";
    if ($exitCode > 0) {
        exit($exitCode);
    }
}

// Remove files and directories if '--cleanup' argument is supplied to save local developers from disasters.
if (isset($argv[1]) && $argv[1] === '--cleanup') {
    foreach ($removables as $removable) {
        if (file_exists($removable)) {
            print "Removing $removable from $dirName\n";
            shell_exec("rm -rf $removable");
        }
    }
}

/**
 * Better shell script execution with live output to STDOUT and status code return.
 * @param  string $command Command to execute in shell.
 * @return int             Exit code.
 */
function executeCommand($command)
{
    $proc = popen("$command 2>&1 ; echo Exit status : $?", 'r');

    $liveOutput     = '';
    $completeOutput = '';

    while (!feof($proc)) {
        $liveOutput     = fread($proc, 4096);
        $completeOutput = $completeOutput . $liveOutput;
        print $liveOutput;
        @ flush();
    }

    pclose($proc);

    // Get exit status.
    preg_match('/[0-9]+$/', $completeOutput, $matches);

    // Return exit status.
    return intval($matches[0]);
}
