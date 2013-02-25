<?php

include_once("config.php");

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

$id = @$_GET['id'];
if (isset($id))
{
	$f = $config["thumb_dir"]."/thumb_".$id.".png";
	if (!file_exists($f)) $f = "html.png";
}
else
	$f = "html.png";

$fp = fopen($f, "r");
if ($fp == false)
	exit(0);

header("Content-type: image/png");

while (!feof($fp))
{
	$data = fread($fp, 1024);
	echo $data;
}

fclose($fp);

?>
