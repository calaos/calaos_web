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
?>
<table style="text-align: center; width: 100%;" border="0"
 cellpadding="2" cellspacing="2"><tbody><tr>
<?php
        $cpt = 0;

        foreach ($rooms as $room => $opt)
        {
                if ($room == "Internal") continue;

                $rname = getRoomTypeString($room);
?>
<td style="text-align: center; width: 25%;">
<div class="room"><?php echo $rname; ?></div>
<a href="javascript:ShowRoom('<?php echo $room; ?>',<?php echo $opt[1]; ?>);">
<img alt="room" src="img/<?php echo getRoomTypeIcon($room); ?>">
</a></td>
<?php
                $cpt++;
                if ($cpt >= 4)
                {
                        $cpt = 0;
                        echo "</tr><tr>";
                }
        }
?>
</tr>
</tbody>
</table>
<?php
        $calaos->Clean();
?>