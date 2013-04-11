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
  echo "Error Opening /tmp/local_config.xml";
  return -1;
}


while($xml->read())
{
  if ($xml->name == "calaos:option")
  {
    if ($xml->getAttribute("name") == "hostame")
    {
      if (!file_put_contents("/etc/hostname", $xml->getAttribute("value")))
      {
        echo "Error writing hostname";
        return -1;
      }
    }
  }
}

if (!rename("/tmp/local_config.xml", getenv("HOME") + "/.config/calaos/local_config.xml"))
{
  echo "Error renaming local_config.xml file";
  return -1;
}

?>