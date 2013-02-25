<?php
	require "auth.php";
	
	$file = @$_GET["file"];

	if (!isset($file)) die("No filename given!");

	$file = "/mnt/ext3/calaos/" . $file;
	$content = file_get_contents($file);

	if ($content == FALSE)
		die("Can't open file");

	if (substr(strrchr($file, '.'), 1) == "xml")
		header("Content-Type: text/xml");

	echo($content);
?>
