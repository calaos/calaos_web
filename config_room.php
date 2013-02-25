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
                $cpt = 0;
                for ($j = 2;$j < count($res2);$j++)
                {
                        list($id, $opt, $value) = explode(":", urldecode($res2[$j]), 3);
//                         if ($opt == "hits" && $value > $max_hits)
//                                 $max_hits = $value;

                        if ($opt == "name")
                                $name = $value;
                        if ($opt == "hits")
                                $hits = $value;

                        if ($name != "" && $hits != "")
                        {
                                $rooms[] = array($name, $hits, $room, $cpt);
                                $name = "";
                                $hits = "";
                                $cpt++;
                        }
                }
        }
?>
<h1 class="title">Edition des pièces :</h1>
<table width=90% style="border: 1px;margin-top:1em;" border="0" cellpadding="0" cellspacing="0">
<tr class="list_header">
<td></td>
<td>Nom de la pièce:</td>
<td>Type de pièce:</td>
<td>Indice:</td>
<td></td>
</tr>
<?php
        for ($i = 0;$i < count($rooms);$i++)
        {
                $room = $rooms[$i];
?>
<tr class="list_element">
<td style="text-align: center; width: 30px;">
<a href="#" onclick="delete_room(<?php echo "'".$room[2]."',".$room[3]; ?>); return true;"><img id="del" alt="del" src="img/trash.png" /></a></td>
<td>
<p id="<?php echo "room_name_".$i; ?>" name="<?php echo "room_name,".$room[2].",".$room[3]; ?>" class="inlineEdit" dojoType="inlineEditBox" onSave="room_edit_cb"><?php echo $room[0]; ?></p>
</td>
<td>
<p id="<?php echo "room_type_".$i; ?>" name="<?php echo "room_type,".$room[2].",".$room[3]; ?>" class="inlineEdit" dojoType="inlineEditBox" onSave="room_edit_cb"><?php echo $room[2]; ?></p>
</td>
<td>
<p id="<?php echo "room_hits_".$i; ?>" name="<?php echo "room_hits,".$room[2].",".$room[3]; ?>" class="inlineEdit" dojoType="inlineEditBox" onSave="room_edit_cb"><?php echo $room[1]; ?></p>
</td>
<td><button dojoType="Button" onclick="EditRoomIO(<?php echo "'".$room[2]."',".$room[3].",'".rawurlencode($room[0])."'"; ?>); return true;">
<div class="inside_button">Editer</div></button></td>
</tr>
<?php
        }
?>
</table>
<p>
<button dojoType="Button" onclick="AddRoom(); return true;">
<div class="inside_button">Ajouter une pi&egrave;ce</div></button>
</p>