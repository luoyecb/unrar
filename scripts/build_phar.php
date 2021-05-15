<?php
// Note: need set phar.readonly=true in php.ini
include __DIR__ . '/../vendor/autoload.php';

use Luoyecb\ArgParser;

function execMain() {
	$parser = new ArgParser();
	$parser->addBool('help', false, 'Show this help information.')
		->addBool('execMode', true, 'Can execute?')
		->addString('dir', '', 'Build from the directory.')
		->addString('output', '', 'Output phar file name.')
		->addString('index', '', 'Bootstrap file.')
		->parse();
	extract($parser->getOptions());

	global $argc;
	if ($help || $argc == 1 || empty($dir) || empty($output)) {
		echo $parser->buildUsage();
		echo PHP_EOL;
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

if (PHP_SAPI == "cli") {
	execMain();
}
