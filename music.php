<?php
        //Check user identity
        require "auth.php";
        require_once "Utils.php";
        require_once "Calaos.php";

        $calaos = Calaos::Instance();

        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

        //if camera_id is set, send back the picture to the browser
        $id = @$_GET['player_id'];
        if (isset($id))
                get_cover_pic($id, @$_GET['width'], @$_GET['height']);

        $calaos->Clean();
?>
