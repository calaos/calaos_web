<head>
  
  <!-- start: Meta -->
  <meta charset="utf-8">
  <title>Calaos Admin</title>
  <meta name="description" content="Calaos Admin">
  <!-- end: Meta -->

  
  <!-- start: CSS -->
  <link id="bootstrap-style" href="assets/css/bootstrap.min.css" rel="stylesheet">
  <link id="bootstrap-style" href="assets/css/calaos" rel="stylesheet">
  <link href="assets/css/bootstrap-responsive.min.css" rel="stylesheet">
  <style type="text/css">
  body {
    padding-top: 60px;
    padding-bottom: 40px;
  }
  .sidebar-nav {
    padding: 9px 0;
  }

  @media (max-width: 980px) {
        /* Enable use of floated navbar text */
        .navbar-text.pull-right {
          float: none;
          padding-left: 5px;
          padding-right: 5px;
        }
      }
    </style>
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
	  <a class="brand" href="index.php" ><img alt="calaos" src="assets/img/logo_calaos.png"></a>
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
        <div class="span3">
        <!-- start: Main Menu -->
        <div class="well sidebar-nav calaos-sidenav">
          <ul class="nav nav-list">
            <li class="nav-header"><a href="#"><i class="icon-home"></i>Ma Maison</a></li>
            
<?php
        //Check user identity
        require "auth.php";

        require_once "Calaos.php";

        $calaos = Calaos::Instance();

        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

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
        }

        $cpt = 0;

        foreach ($rooms as $room => $opt)
        {
                if ($room == "Internal") continue;

                $rname = getRoomTypeString($room);
?>
<li>
<a href="javascript:ShowRoom('<?php echo $room; ?>',<?php echo $opt[1]; ?>);">
<img height=48 width=48 src="assets/img/<?php echo getRoomTypeIcon($room); ?>"><img>
<?php echo $rname; ?>
</a></li>
<?php
        }
        $calaos->Clean();
?>

            <li class="nav-header"><a href="javascript:graphs();"><img width=16 height=16 src="assets/img/glyphicons_040_stats.png"></img>Graphiques</a></li>
          </ul>
        </div>
        </div>
        <!-- end: Main Menu -->

    
    