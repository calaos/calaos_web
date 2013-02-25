<?php
        //Check user identity
        require "../auth.php";
        require_once "../Calaos.php";
        require_once "../Utils.php";

        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

        //if camera_id is set, send back the picture to the browser
        $playerid = @$_GET['player_id'];
        $playerfs = @$_GET['player_fs'];
        if (isset($playerid) && !isset($playerfs))
                get_cover_pic($playerid, @$_GET['width'], @$_GET['height']);

        if (isset($playerfs)) $playerid = $playerfs;

        header("Content-type: text/xml");
?>
<root>
        <title set="waMusic<?php if (isset($playerfs)) echo "Single"; ?>"><![CDATA[Ma Musique]]></title>
        <part>
                <destination mode="replace" zone="waMusic<?php if (isset($playerfs)) echo "Single"; ?>" create="true" />
                <data><![CDATA[ <?php if (isset($playerfs)) echo DataSingle($playerid); else echo Data(); ?> ]]></data>
        </part>
</root>

<?php

        function Data()
        {
                $calaos = Calaos::Instance();
                $res = explode(" ", $calaos->SendRequest("audio ?"));
                $nb = urldecode($res[1]);
                if ($nb == "?") $nb = 0;
?>
<div class="iMenu">
        <h3>Mes zones de musique:</h3>
        <ul class="iArrow">
<?php
                for ($i = 0;$i < $nb;$i++)
                {
                        $res = explode(" ", $calaos->SendRequest("audio get ".$i));

                        for ($j = 0;$j < count($res);$j++)
                        {
                                list($opt, $val) = explode(":", urldecode($res[$j]), 2);
                                if ($opt == "name")
                                        $name = urldecode($val);
                        }
?>
<li><a href="music.php?player_fs=<?php echo $i; ?>#_MusicSingle" rev="async" onclick="return true;"><img id="audio_<?php echo $i; ?>" src="music.php?dummy=<?php echo time();?>&player_id=<?php echo $i; ?>" width="62" height="62" /><em><?php echo $name; ?></em></a></li>
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

                $res = explode(" ", $calaos->SendRequest("audio get ".$i));
                for ($j = 0;$j < count($res);$j++)
                {
                        list($opt, $val) = explode(":", urldecode($res[$j]), 2);
                        if ($opt == "name")
                                $name = urldecode($val);
                }

                $res = explode(" ", $calaos->SendRequest("audio ".$i." songinfo?"));
                for ($j = 0;$j < count($res);$j++)
                {
                        list($opt, $val) = explode(":", urldecode($res[$j]), 2);
                        if ($opt == "title") $title = urldecode($val);
                        if ($opt == "artist") $artist = urldecode($val);
                        if ($opt == "album") $album = urldecode($val);
                }
?>
<div class="iBlock">
<h3><?php echo $name ?></h3>
<div><img style="display: block; margin: 8px auto; width:150px; height:150px;" id="player_single_<?php echo $i; ?>" src="music.php?dummy=<?php echo time();?>&player_id=<?php echo $i; ?>" width="150" height="150" />
<p id="song_infos" style="text-align:center;">
        <?php echo $artist; ?><br />
        <i><?php echo $title; ?></i><br />
        <i><?php echo $album; ?></i>
</p>
</div>

<div>
<table style="margin: 8px auto;">
<tr>
        <td><a class="black button" style="width:30px" onclick="player_previous('<?php echo $i; ?>'); return false;"><img style="margin-top: 12px;" alt="previous" src="img/icon_previous.png" /></a></td>
        <td><a class="black button" style="width:30px" onclick="player_play('<?php echo $i; ?>'); return false;"><img style="margin-top: 12px;" alt="play" src="img/icon_play.png" /></a></td>
        <td><a class="black button" style="width:30px" onclick="player_stop('<?php echo $i; ?>'); return false;"><img style="margin-top: 12px;" alt="stop" src="img/icon_stop.png" /></a></td>
        <td><a class="black button" style="width:30px" onclick="player_next('<?php echo $i; ?>'); return false;"><img style="margin-top: 12px;" alt="next" src="img/icon_next.png" /></a></td>
</tr>
</table>
</div></div>
<?php
                $calaos->Clean();
        }
?>
