<?php

        require "../auth.php";
        require_once "../Utils.php";
        require_once "../Calaos.php";

        $calaos = Calaos::Instance();

        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

        include "header.php";
?>
<p class="title"><a href="menu.php"><img alt="back" src="img/rtl-bullet-media.png" /></a> Calaos: <em>Mes Caméras</em></p>
<div class="Menu">
<ul id="main_menu">
<?php
        $calaos = Calaos::Instance();
        $res = explode(" ", $calaos->SendRequest("camera ?"));
        $nb = urldecode($res[1]);
        if ($nb == "?") $nb = 0;

        for ($i = 0;$i < $nb;$i++)
        {
                $res = explode(" ", $calaos->SendRequest("camera get ".$i));

                for ($j = 0;$j < count($res);$j++)
                {
                        list($opt, $val) = explode(":", urldecode($res[$j]), 2);
                        if ($opt == "name")
                                $name = urldecode($val);
                        if ($opt == "ptz")
                                $ptz = $val;
                }
?>
        <li class="ArrowMore"><a class="ItemContent" href="camera_full.php?cam_id=<?php echo $i; ?>">
                <img id="camera_<?php echo $i; ?>" src="../camera.php?camera_id=<?php echo $i; ?>&width=82&height=62" width="82" height="62" />
                <em><?php echo $name; ?></em>
        </a></li>
<?php
        }

        $calaos->Clean();
?>
</ul>
</div>
<?php
        include "footer.php";
?>