<?php
	require "auth.php";

	function uploadFile($file, $need_xml_check = false)
	{
		$content_dir = '/mnt/ext3/calaos/';

		$tmp_file = $_FILES[$file]['tmp_name'];

		if(!is_uploaded_file($tmp_file))
        		die("Le fichier est introuvable");

		if ($need_xml_check)
		{
			if (simplexml_load_file($tmp_file) == FALSE) die ("XML Parse error");
		}

		$name_file = $_FILES[$file]['name'];

		if(!@move_uploaded_file($tmp_file, $content_dir . $name_file))
			die("Erreur de copie");
	}
	
	if(isset($_POST['upload']))
	{
		uploadFile("io", true);
		uploadFile("rules", true);
	}
	else
	{
		die("Pas d'upload");
	}

	echo("OK");
?>
