<?php

        require "../auth.php";
        require_once "../Utils.php";
        require_once "../Calaos.php";

        $camid = @$_GET['cam_id'];
        if (!isset($camid))
                die("Camera ID not set !");

        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

        include "header.php";
?>
<?php
        $calaos = Calaos::Instance();

        $res = explode(" ", $calaos->SendRequest("camera get ".$camid));
        for ($j = 0;$j < count($res);$j++)
        {
                list($opt, $val) = explode(":", urldecode($res[$j]), 2);
                if ($opt == "name")
                        $name = urldecode($val);
                if ($opt == "ptz")
                        $ptz = $val;
        }

        $calaos->Clean();
?>
<p class="title"><a href="camera.php"><img alt="back" src="img/rtl-bullet-media.png" /></a> Camera: <em><?php echo $name; ?></em></p>
<div class="Block">
<img style="display: block; margin: 8px auto; width:280px; height:210px;" id="camera_single_<?php echo $camid; ?>" src="../camera.php?camera_id=<?php echo $camid; ?>&width=200&height=153" width="280" height="210" />
</div>
<?php
        include "footer.php";
?>