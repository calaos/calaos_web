<?php

        require "../auth.php";
        require_once "../Utils.php";
        require_once "../Calaos.php";

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

//                 $max_hits = 0;
                for ($j = 2;$j < count($res2);$j++)
                {
                        list($id, $opt, $value) = explode(":", urldecode($res2[$j]), 3);

                        if ($opt == "name")
                                $rooms[] = array($room, $value, $id);

//                         if ($opt == "hits" && $value > $max_hits)
//                                 $max_hits = $value;
                }

//                 $rooms[$room] = array($max_hits, $count);
        }

        include "header.php";
?>
<p class="title"><a href="menu.php"><img alt="back" src="img/rtl-bullet-media.png" /></a> Calaos: <em>Ma maison</em></p>
<div class="Menu">
<ul id="main_menu">
<?php
        foreach ($rooms as $room)
        {
                if ($room[0] == "Internal" && $room[1] == "SimpleScenario") continue;
?>
        <li class="ArrowMore"><a class="ItemContent" href="room.php?room_type=<?php echo $room[0]; ?>&room_id=<?php echo $room[2]; ?>&room_name=<?php echo @rawurlencode($room[1]); ?>">
                <img src="../img/<?php echo getRoomTypeIcon($room[0]); ?>" width="112" height="62" />
                <em><?php echo $room[1]; ?></em>
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