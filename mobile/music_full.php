<?php

        require "../auth.php";
        require_once "../Utils.php";
        require_once "../Calaos.php";

        $playerid = @$_GET['player_id'];
        if (!isset($playerid))
                die("Player ID not set !");

        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

        include "header.php";
?>
<?php
        $calaos = Calaos::Instance();

        $res = explode(" ", $calaos->SendRequest("audio get ".$playerid));
        for ($j = 0;$j < count($res);$j++)
        {
                list($opt, $val) = explode(":", urldecode($res[$j]), 2);
                if ($opt == "name")
                        $name = urldecode($val);
        }

        $res = explode(" ", $calaos->SendRequest("audio ".$playerid." songinfo?"));
        for ($j = 0;$j < count($res);$j++)
        {
                list($opt, $val) = explode(":", urldecode($res[$j]), 2);
                if ($opt == "title") $title = urldecode($val);
                if ($opt == "artist") $artist = urldecode($val);
                if ($opt == "album") $album = urldecode($val);
        }

        $calaos->Clean();
?>
<p class="title"><a href="camera.php"><img alt="back" src="img/rtl-bullet-media.png" /></a> Zone: <em><?php echo $name; ?></em></p>
<div class="Block">
<img style="display: block; margin: 8px auto; width:150px; height:150px;" id="player_single_<?php echo $playerid; ?>" src="../iphone/music.php?dummy=<?php echo time();?>&player_id=<?php echo $playerid; ?>" width="150" height="150" />
<p id="song_infos" style="text-align:center;">
        <?php echo $artist; ?><br />
        <i><?php echo $title; ?></i><br />
        <i><?php echo $album; ?></i>
</p>
<table style="margin: 8px auto;">
<tr>
        <td><a href="#" onclick="player_previous('<?php echo $playerid; ?>'); return false;"><img alt="go" src="img/icon_previous.png" /></a></td>
        <td><a href="#" onclick="player_play('<?php echo $playerid; ?>'); return false;"><img alt="go" src="img/icon_play.png" /></a></td>
        <td><a href="#" onclick="player_stop('<?php echo $playerid; ?>'); return false;"><img alt="go" src="img/icon_stop.png" /></a></td>
        <td><a href="#" onclick="player_next('<?php echo $playerid; ?>'); return false;"><img alt="go" src="img/icon_next.png" /></a></td>
</tr>
</table>
</div>
<?php
        include "footer.php";
?>