<?php
        //Check user identity
        require "auth.php";

        require_once "Calaos.php";

        $calaos = Calaos::Instance();

        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

        $id = @$_GET['id'];
        $io_type = @$_GET['type'];
        if (!isset($io_type))
                die ("Error: type not set...");

        if ($io_type != "output" && $io_type != "input")
                die ("Error: wrong type...");

        $room_id = @$_GET['room_id'];
        if (!isset($room_id))
                die ("Error: room_id not set...");
        $room_type = @$_GET['room_type'];
        if (!isset($room_type))
                die ("Error: room_type not set...");
        $room_name = @urldecode($_GET['room_name']);
        if (!isset($room_name))
                die ("Error: room_name not set...");


?>
<h1 class="title">Edition des Entr&eacute;es/Sorties de : <em><?php echo $room_name; ?></em></h1>
<em><a href="javascript:ShowIO();">Configuration</a> > <a href="javascript:EditRoomIO(<?php echo "'".$room_type."',".$room_id.",'".urlencode($room_name)."'"; ?>);"><?php echo $room_name; ?></a> > Edition</em>
<table width="90%" style="border: 1px;margin-top:1em;" border="0" cellpadding="0" cellspacing="1">
<tr class="list_header">
<td style="background-color:white;"></td>
<td>Param&egrave;tre:</td>
<td>Valeur:</td>
</tr>
<?php

        $res = explode(" ", $calaos->SendRequest($io_type." ".$id." params?"));
        for ($i = 1;$i < count($res);$i++)
        {
                list($key, $val) = explode(":", urldecode($res[$i]), 2);
                if ($key == "type") $type = $val;
?>
<tr class="list_element">
<td style="text-align: center; width: 30px;">
<a href="#" onclick="delete_ioparam(<?php echo "'".$io_type."','".$id."','".$key."','$room_type',$room_id,'".urlencode($room_name)."'"; ?>); return true;"><img id="del" alt="del" src="img/trash.png" /></a></td>
<td>
<?php echo $key; ?>
</td>
<td>
<p id="<?php echo "ioparam_".$i; ?>" name="<?php echo $io_type.",".$id.",".$key; ?>" class="inlineEdit" dojoType="inlineEditBox" onSave="ioparam_edit_cb"><?php echo $val; ?></p>
</td>
</tr>
<?php
        }
?>
</table>
<p>
<button dojoType="Button" onclick="AddIOParam('<?php echo $io_type; ?>','<?php echo $id; ?>','<?php echo $room_type; ?>',<?php echo $room_id; ?>,'<?php echo urlencode($room_name); ?>'); return true;">
<div class="inside_button">Ajouter un param&egrave;tre</div></button>
</p>
<?php
        if ($type == "InPlageHoraire")
        {
                $res = explode(" ", $calaos->SendRequest("input ".$id." plage get"));
                for ($i = 2;$i < count($res);$i++)
                {
                        $couple = array();
                        list($day, $hour1, $hour2) = explode(":", urldecode($res[$i]), 3);
                        $couple[] = urldecode($hour1);
                        $couple[] = urldecode($hour2);

                        if ($day == 1) $plage["Lundi"][] = $couple;
                        if ($day == 2) $plage["Mardi"][] = $couple;
                        if ($day == 3) $plage["Mercredi"][] = $couple;
                        if ($day == 4) $plage["Jeudi"][] = $couple;
                        if ($day == 5) $plage["Vendredi"][] = $couple;
                        if ($day == 6) $plage["Samedi"][] = $couple;
                        if ($day == 7) $plage["Dimanche"][] = $couple;
                }
?>
<table width="90%" style="border: 2px solid grey;margin-top:1em;" border="0" cellpadding="1" cellspacing="1">
<tr class="list_header">
<td>Jour</td>
<td>Plages horaires</td>
</tr>
<?php
        for ($j = 1;$j < 8;$j++)
        {
                if ($j == 1) $daystr = "Lundi";
                if ($j == 2) $daystr = "Mardi";
                if ($j == 3) $daystr = "Mercredi";
                if ($j == 4) $daystr = "Jeudi";
                if ($j == 5) $daystr = "Vendredi";
                if ($j == 6) $daystr = "Samedi";
                if ($j == 7) $daystr = "Dimanche";
?>
<tr>
<td width="40%" style="vertical-align: top;"><table width="90%" style="text-align: left; width: 100%;" border="0" cellpadding="0" cellspacing="0"><tbody>
<tr><td width="90%"><?php echo $daystr; ?></td>
<td style="text-align: right;"><button dojoType="Button" onclick="AddHour(<?php echo "$j,'$id','$room_type',$room_id,'".urlencode($room_name)."'"; ?>); return true;">
<div class="inside_button">+</div></button></td></tr>
</tbody></table></td>
<td>
<?php
                for ($i = 0;$i < count($plage[$daystr]);$i++)
                {
                        $couple = $plage[$daystr][$i];
                        $hour1 = $couple[0];
                        $hour2 = $couple[1];
?>
<table width="90%" style="text-align: left; width: 100%;" border="0" cellpadding="0" cellspacing="0"><tbody><tr>
<td width="10px"><a href="javascript:delete_plage(<?php echo "$j,$i,'$id','$room_type',$room_id,'".urlencode($room_name)."'"; ?>);"><img id="del" alt="del" src="img/trash.png" /></a></td>
<td width="40%"><p id="<?php echo "ioplage1_".$j."_".$i; ?>" name="<?php echo "$j,$i,$hour2,$id,$room_type,$room_id,".urlencode($room_name); ?>" class="inlineEdit" dojoType="inlineEditBox" onSave="plage_edit_hour1_cb"><?php echo $hour1; ?></p></td>
<td width="10px"><img alt="arrow" src="img/arrowright.png" /></td>
<td width="40%"><p id="<?php echo "ioplage2_".$j."_".$i; ?>" name="<?php echo "$j,$i,$hour1,$id,$room_type,$room_id,".urlencode($room_name); ?>" class="inlineEdit" dojoType="inlineEditBox" onSave="plage_edit_hour2_cb"><?php echo $hour2; ?></p></td>
</tr></tbody></table>
<?php
                        if ($i < count($plage[$daystr]) - 1)
                                echo "<br />";
                }
?>
</td>
</tr>
<?php
                if ($j < 7)
                {
?>
<tr>
<td><hr style="border: 1px solid #EEE;" /></td><td><hr style="border: 1px solid #EEE;" /></td>
</tr>
<?php
                }
        }
?>
</table>
<?php
        }
?>