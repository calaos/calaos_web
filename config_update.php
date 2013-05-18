#!/usr/bin/php

<?php 

if (!file_exists("/run/calaos/config/local_config.xml"))
{
  echo "Nothing to do; exiting";
  return 0;
}

$xml = new XMLReader();

if (!$xml->open("/run/calaos/config/local_config.xml"))
{
  echo "Error Opening /run/calaos/config/local_config.xml\n";
  return -1;
}


if (!file_exists("/etc/calaos/"))
{
  echo "Creating /etc/calaos/";
  mkdir("/etc/calaos/", 0755, true);
}

if (!rename("/run/calaos/config/local_config.xml", "/etc/calaos/local_config.xml"))
{
  echo "Error renaming local_config.xml file\n";
  return -1;
}

while($xml->read())
{
  if ($xml->name == "calaos:option")
  {
    if ($xml->getAttribute("name") == "hostname")
    {
	// Not really safe isn't it ? but how to do that safely in php ?
	exec("hostnamectl set-hostname \"" . $xml->getAttribute("value") . "\"");	
    }
    if ($xml->getAttribute("name") == "start_calaos_server")
    {
	if ($xml->getAttribute("value") == "true")
	    exec("systemctl enable calaos-server");
	else
	    exec("systemctl disable calaos-server");
    }

    if ($xml->getAttribute("name") == "start_calaos_home")
    {
	if ($xml->getAttribute("value") == "true")
	    exec("systemctl enable calaos-home");
	else
	    exec("systemctl disable calaos-home");
    }
    if ($xml->getAttribute("name") == "hostname")
    {
      
    }
    if ($xml->getAttribute("name") == "start_shairport")
    {
	if ($xml->getAttribute("value") == "true")
	    exec("systemctl enable shairport");
	else
	    exec("systemctl disable shairport");
    }
  }
}

echo "Finish!\n"

?>