<?php
        //Check user identity
        require "../auth.php";
        require_once "../Calaos.php";
        require_once "../Utils.php";

        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

        //if camera_id is set, send back the picture to the browser
        $camid = @$_GET['camera_id'];
        $camfs = @$_GET['camera_fs'];
        if (isset($camid) && !isset($camfs))
                get_camera_pic($camid);

        if (isset($camfs)) $camid = $camfs;

        header("Content-type: text/xml");
?>
<root>
        <title set="waCamera<?php if (isset($camfs)) echo "Single"; ?>"><![CDATA[Mes Cam&eacute;ras]]></title>
        <part>
                <destination mode="replace" zone="waCamera<?php if (isset($camfs)) echo "Single"; ?>" create="true" />
                <data><![CDATA[ <?php if (isset($camfs)) echo DataSingle($camid); else echo Data(); ?> ]]></data>
        </part>
</root>

<?php

        function Data()
        {
                $calaos = Calaos::Instance();
                $res = explode(" ", $calaos->SendRequest("camera ?"));
                $nb = urldecode($res[1]);
                if ($nb == "?") $nb = 0;
?>
<div class="iMenu">
        <h3>Mes Cam&eacute;ras disponibles:</h3>
        <ul class="iArrow">
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
<li><a href="camera.php?camera_fs=<?php echo $i; ?>#_CameraSingle" rev="async" onclick="start_camera_single('<?php echo $i; ?>'); return true;"><img id="camera_<?php echo $i; ?>" src="../camera.php?camera_id=<?php echo $i; ?>&width=82&height=62" width="82" height="62" /><em><?php echo $name; ?></em></a></li>
<?php
                }
?>
        </ul>
</div>
<?php
                $calaos->Clean();
        }

        function DataSingle($i)
        {
                $calaos = Calaos::Instance();

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
<div class="iBlock">
        <h3>Cam&eacute;ra: <br /><i><?php echo $name ?></i></h3>
        <div><img style="display: block; margin: 8px auto; width:280px; height:210px;" id="camera_single_<?php echo $i; ?>" src="../camera.php?camera_id=<?php echo $i; ?>&width=200&height=153" width="280" height="210" /></div>
</div>
<?php
                $calaos->Clean();
        }
?>