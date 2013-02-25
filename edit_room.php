<?php
        //Check user identity
        require "auth.php";

        require_once "Calaos.php";

        $calaos = Calaos::Instance();

        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

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
<h1 class="title">Edition de la pi&egrave;ce : <em><?php echo $room_name; ?></em></h1>
<em><a href="javascript:ShowIO();">Configuration</a> > <?php echo $room_name; ?></em>
<table width=90% style="border: 1px;margin-top:1em;" border="0" cellpadding="2" cellspacing="2">
<tr class="list_header">
<td style="background-color:white;"></td>
<td>Type:</td>
<td>Nom:</td>
<td>ID:</td>
<td style="background-color:white;"></td>
</tr>
<?php

        $res = explode(" ", $calaos->SendRequest("room ".$room_type." get ".$room_id));
        for ($i = 1;$i < count($res);$i++)
        {
                list($opt, $val) = explode(":", urldecode($res[$i]), 2);

                if ($opt == "input")
                {
                        $stype = "Entr&eacute;e";
                        $res2 = explode(" ", $calaos->SendRequest("input ".$val." get"));
                        for ($j = 1;$j < count($res2);$j++)
                        {
                                list($opt2, $val2) = explode(":", urldecode($res2[$j]), 2);
                                if ($opt2 == "name")
                                        $name = $val2;
                                if ($opt2 == "type")
                                        $type = $val2;
                                if ($opt2 == "id")
                                        $iid = $val2;
                        }

                        if ($type == "WITemp") $stype = "Sonde de Temp&eacute;rature";
                        if ($type == "scenario") $stype = "Sc&eacute;nario";
                        if ($type == "CamInput" || $type == "AudioInput" || $type == "IRInput" ||
                            $type == "InternalBool" || $type == "InternalInt" || $type == "InternalString")
                                continue;
?>
<tr class="list_element">
<td style="text-align: center; width: 30px;">
<a href="#" onclick="delete_input(<?php echo "'$val','$room_type',$room_id,'".urldecode($room_name)."'"; ?>); return true;"><img id="del" alt="del" src="img/trash.png" /></a></td>
<td><?php echo $stype; ?></td>
<td><?php echo $name; ?></td>
<td><?php echo $val; ?></td>
<td><button dojoType="Button" onclick="EditInput('<?php echo $iid; ?>','<?php echo $room_type; ?>',<?php echo $room_id; ?>,'<?php echo rawurlencode($room_name); ?>'); return true;">
<div class="inside_button">Editer</div></button></td>
<?php
                }
                else if ($opt == "output")
                {
                        $res2 = explode(" ", $calaos->SendRequest("output ".$val." get"));
                        $stype = "Sortie";
                        for ($j = 1;$j < count($res2);$j++)
                        {
                                list($opt2, $val2) = explode(":", urldecode($res2[$j]), 2);
                                if ($opt2 == "name")
                                        $name = $val2;
                                if ($opt2 == "type")
                                        $type = $val2;
                                if ($opt2 == "id")
                                        $oid = $val2;
                        }

                        if ($type == "CamInput" || $type == "AudioInput" || $type == "IRInput" || $type == "InputTimer" ||
                            $type == "scenario")
                        {
                                continue;
                        }

                        if ($type == "CamOutput") $stype = "Cam&eacute;ra";
                        if ($type == "AudioOutput") $stype = "Lecteur Audio";
                        if ($type == "IROutput") $stype = "Module IR";
                        if ($type == "InternalBool") $stype = "Variable (bool&eacute;en)";
                        if ($type == "InternalInt") $stype = "Variable (num&eacute;rique)";
                        if ($type == "InternalString") $stype = "Variable (texte)";
?>
<tr class="list_element">
<td style="text-align: center; width: 30px;">
<a href="#" onclick="delete_output(<?php echo "'$val','$room_type',$room_id,'".urldecode($room_name)."'"; ?>); return true;"><img id="del" alt="del" src="img/trash.png" /></a></td>
<td><?php echo $stype; ?></td>
<td><?php echo $name; ?></td>
<td><?php echo $val; ?></td>
<td><button dojoType="Button" onclick="EditOutput('<?php echo $oid; ?>','<?php echo $room_type; ?>',<?php echo $room_id; ?>,'<?php echo rawurlencode($room_name); ?>'); return true;">
<div class="inside_button">Editer</div></button></td>
<?php
                }
?>
</tr>
<?php
        }
?>
</table>
<p>
<button dojoType="Button" onclick="CreateIO(<?php echo "'$room_type',$room_id,'".rawurlencode($room_name)."'"; ?>); return true;">
<div class="inside_button">Ajouter p&eacute;riph&eacute;rique</div></button>
</p>
