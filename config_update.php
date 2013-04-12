#!/usr/bin/php

<?php 

if (!file_exists("/tmp/local_config.xml"))
{
  echo "Nothing to do; exiting";
  return 0;
}

$xml = new XMLReader();

if (!$xml->open("/tmp/local_config.xml"))
{
  echo "Error Opening /tmp/local_config.xml\n";
  return -1;
}


if (!file_exists(getenv("HOME") + "/.config/calaos/"))
{
  echo "Creating " . getenv("HOME") . "/.config/calaos/\n";
  mkdir(getenv("HOME") . "/.config/calaos/", 0777, true);
}

if (!rename("/tmp/local_config.xml", getenv("HOME") . "/.config/calaos/local_config.xml"))
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
      if (!file_put_contents("/etc/hostname", $xml->getAttribute("value") . "\n"))
      {
        echo "Error writing hostname\n";
        return -1;
      }
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