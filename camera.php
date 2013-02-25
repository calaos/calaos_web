<?php
        //Check user identity
        require "auth.php";
        require_once "Utils.php";
        require_once "Calaos.php";

        $calaos = Calaos::Instance();

        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

        //if camera_id is set, send back the picture to the browser
        $camid = @$_GET['camera_id'];
        if (isset($camid))
                get_camera_pic($camid, @$_GET['width'], @$_GET['height']);

        //else list the camera define on the server
        $res = explode(" ", $calaos->SendRequest("camera ?"));
        $nb = urldecode($res[1]);
        if ($nb == "?") $nb = 0;
?>
<h1 class="list_header">Liste des caméras disponibles :</h1>
<table width=90% style="border: 1px;">
<?php
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
<tr class="list_element">
<td style="text-align: center; width: 30px;"><img style="width: 24px; height: 24px;" alt="camera" src="img/camera.png" /></td>
<td><?php echo $name; ?></td>
<td style="width: 150px;">
<button style="width: 150px;" dojoType="Button" onclick="OpenCamera('<?php echo $i; ?>','<?php echo $name."',".$ptz; ?>)">
<div class="inside_button">
<img src="img/voir.gif" height=16 alt="voir" />Voir</div></button></td></tr>
<?php
        }
?>
</table>
<?php
        $calaos->Clean();
?>
