<?php
// Note: need set phar.readonly=true in php.ini
include __DIR__ . '/../vendor/autoload.php';

use Luoyecb\ArgParser;

function execMain() {
	$parser = new ArgParser();
	$parser->addBool('help', false);
	$parser->addBool('execMode', true);
	$parser->addString('dir', '');
	$parser->addString('output', '');
	$parser->addString('index', '');
	$parser->parse();
	extract($parser->getOptions());

	global $argc;
	if ($help || $argc == 1 || empty($dir) || empty($output)) {
		printUsage();
		return;
	}

	$phar = new Phar($output);
	$phar->buildFromDirectory($dir);
	$phar->compressFiles(Phar::GZ);
	if (!empty($index)) {
		$stub = Phar::createDefaultStub($index);
		if ($execMode) {
			$stub = "#!/usr/bin/env php".PHP_EOL.$stub;
		}
		$phar->setStub($stub);
	}
}

function printUsage() {
    global $argv;
    $basename = basename($argv[0]);
    echo <<<"USAGE_STR"
Usage:
    php {$basename} [option]

option:
    -help:     Show this help information.
    -execMode: Can execute?
    -dir:      Build from the directory.
    -output:   Output phar file name.
    -index:    Bootstrap file.
USAGE_STR;
    echo PHP_EOL;
    echo PHP_EOL;
}

if (PHP_SAPI == "cli") {
	execMain();
}
