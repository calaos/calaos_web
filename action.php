<?php
define('ENNA_WWW', 1);
include_once('common.php');
include_once('Utils.php');
define('PAGE_NAV', 0);

require_once('ConfigParser.php');
require_once('DetectServer.php');

$data = file_get_contents("php://input");

header("Content-type: application/json");

$jdata = json_decode($data, true);

if ($jdata == NULL)
{
  //If json_decode failed, we can try to get the json from traditionnal Form POST
  if (isset($_POST["json"]))
  {
    $jdata = json_decode(stripcslashes($_POST["json"]), true);
    if ($jdata == NULL)
    die_error();
  }
  else
{
    die_error();
  }
}

if ($jdata['action'] == 'music_source')
{
  if ($jdata['cmd'] == 'list')
  {
    $d = new DetectServer();
    $d->discover();
    $value = array('action' => 'music_source',
                   'cmd' => 'list',
                   'result' => $d->getServerList());
    die (json_encode($value));
  }
}

if ($jdata['action'] == 'write_config')
{
  foreach ($jdata['value'] as $option => $value) 
  {
     setConfigOption($option, $value);
  }
}

//Error unknown command/action
die_error();
?>