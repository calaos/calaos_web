<head>
  
  <!-- start: Meta -->
  <meta charset="utf-8">
  <title>Calaos Admin</title>
  <meta name="description" content="Calaos Admin">
  <!-- end: Meta -->

  
  <!-- start: CSS -->
  <link id="bootstrap-style" href="assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/bootstrap-responsive.min.css" rel="stylesheet">
  <link href="assets/css/docs.css" rel="stylesheet">
  <!-- end: CSS -->

  <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
  <!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
  <link id="ie-style" href="assets/css/ie.css" rel="stylesheet">
  <![endif]-->
  
  <!--[if IE 9]>
  <link id="ie9style" href="assets/css/ie9.css" rel="stylesheet">
  <![endif]-->
  
  <!-- start: Favicon -->
  <link rel="shortcut icon" href="assets/img/favicon.ico">
  <!-- end: Favicon -->
  

  <body>
    <!-- start: Header -->
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
	  <a class="brand"><img alt="calaos" src="assets/img/logo_calaos.png"></a>
          <a class="brand" href="index.php">Calaos</a> 
          <a class="btn btn-navbar collapsed" data-target=".top-nav.nav-collapse,.sidebar-nav.nav-collapse" data-toggle="collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
        </div>
      </div>
    </div>
    <!-- start: Header -->
    
    <div class="container-fluid">
      <div class="row-fluid">
        <!-- start: Main Menu -->
        <div class="well sidebar-nav">
          <ul class="nav nav-list">
            <li class="nav-header"><a href="#"><i class="icon-home"></i>Ma Maison</a></li>
<?php

require_once "Calaos.php";

$calaos = Calaos::Instance();


//get the number of rooms
$res = explode(" ", $calaos->SendRequest("home ?"));

for ($i = 1;$i < count($res);$i++)
{
  list($room, $count) = explode(":", urldecode($res[$i]), 2);

  $res2 = explode(" ", $calaos->SendRequest("home get ".$room));


  list($str, $countr) = explode(":", urldecode($res2[1]), 2);
  if ($countr <= 0) continue;

  $max_hits = 0;
  for ($j = 2;$j < count($res2);$j++)
  {
    list($id, $opt, $value) = explode(":", urldecode($res2[$j]), 3);
    if ($opt == "hits" && $value > $max_hits)
    $max_hits = $value;
  }

  $rooms[$room] = array($max_hits, $count);
  $cpt = 0;

  foreach ($rooms as $room => $opt)
  {
    if ($room == "Internal") continue;
    $rname = getRoomTypeString($room);
?>
    <li><a href="#">
<?php
    echo $rname;
?>
    </a></li>
<?php
  }
}
?>
            </ul>
        </div>
        <!-- end: Main Menu -->

      </div>
    </div>
	
        