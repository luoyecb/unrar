<?php
include __DIR__ . "/vendor/autoload.php";

use Luoyecb\ArgParser;

function execMain() {
	$parser = new ArgParser();
	$parser->addString("f", "");
	$parser->addString("d", "");
	$parser->parse();
	extract($parser->getOptions());

	// parse args
	if (empty($f)) {
		echo "Usage: \n\tunrar -f {filename} -d {dir}\n";
		exit();
	}
	$realname = realpath($f);

	if (!empty($d)) {
		$extractDir = $d;
	} else {
		$extractDir = dirname($realname);
	}

	// rar
	$rar = RarArchive::open($realname);
	if ($rar === false) {
		exit("open file error.\n");
	}

	$entries = $rar->getEntries();
	if ($entries === false) {
		exit("open file error.\n");
	}
	if (empty($entries)) {
		exit("No valid entries found.\n");
	}

	// decompression
	foreach ($entries as $entry) {
		$entry->extract($extractDir);
		echo ".";
	}
	echo "\n";

	$rar->close();
	echo "Successfully decompressed.\n";
}

if (PHP_SAPI == "cli") {
	execMain();
}
