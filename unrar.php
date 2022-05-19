<?php
include __DIR__ . "/vendor/autoload.php";

use Luoyecb\ArgParser;

function execMain() {
	$parser = new ArgParser();
	$parser->addString("f", "");
	$parser->addString("d", "");
	$parser->addString("p", "");
	$parser->parse();
	extract($parser->getOptions());

	// parse args
	if (empty($f)) {
		echo "Usage: \n\tunrar -f {filename} -d {dir} -p {password}\n";
		exit();
	}
	$realname = realpath($f);

	if (!empty($d)) {
		$extractDir = $d;
	} else {
		$extractDir = dirname($realname);
	}

	// password
	$password = NULL;
	if (!empty($p)) {
		$password = $p;
	}

	// rar
	$rar = RarArchive::open($realname, $password);
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
