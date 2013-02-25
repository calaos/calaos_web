<?php
        //Check user identity
        require "../auth.php";

        require_once "../Calaos.php";

        $calaos = Calaos::Instance();

        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Content-type: text/xml");

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
?>
<root>
        <title set="waHome">Ma Maison</title>
        <part>
                <destination mode="replace" zone="waHome" create="true" />
                <data><![CDATA[ <?php echo Data(); ?> ]]></data>
        </part>
</root>

<?php

        function Data()
        {
?>
<div class="iMenu">
        <h3>Votre Maison</h3>
        <ul class="iArrow">
<?php
                $cpt = 0;
                global $rooms;

                foreach ($rooms as $room)
                {
                        if ($room[0] == "Internal" && $room[1] == "SimpleScenario") continue;
?>
                <li><a href="room.php?room_type=<?php echo $room[0]; ?>&room_id=<?php echo $room[2]; ?>&room_name=<?php echo @rawurlencode($room[1]); ?>#_Room" rev="async" onclick="return true;"><img src="../img/<?php echo getRoomTypeIcon($room[0]); ?>" width="112" height="62" /><em><?php echo $room[1]; ?></em></a></li>
<?php
                        $cpt++;
                        if ($cpt >= 4)
                        {
                                $cpt = 0;
                                echo "</tr><tr>";
                        }
                }
?>
        </ul>
</div>
<?php
        }
        $calaos->Clean();
?>
