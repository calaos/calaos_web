<?php
        //Check user identity
        require "../auth.php";

        require_once "../Calaos.php";

        $calaos = Calaos::Instance();

        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Content-type: text/xml");

        $room_id = @$_GET['room_id'];
        if (!isset($room_id))
                die ("Error: room_id not set...");
        $room_type = @$_GET['room_type'];
        if (!isset($room_type))
                die ("Error: room_type not set...");
        $room_name = @urldecode($_GET['room_name']);
        if (!isset($room_name))
                die ("Error: room_name not set...");

        $input = array();
        $output = array();

        $res = explode(" ", $calaos->SendRequest("room ".$room_type." get ".$room_id));
        for ($i = 1;$i < count($res);$i++)
        {
                list($opt, $val) = explode(":", urldecode($res[$i]), 2);

                if ($opt == "input")
                        $input[] = $val;
                else if ($opt == "output")
                        $output[] = $val;
        }
?>
<root>
        <title set="waRoom">Actions de la piece</title>
        <part>
                <destination mode="replace" zone="waRoom" create="true" />
                <data><![CDATA[ <?php echo Data(); ?> ]]></data>
        </part>
</root>

<?php

        function Data()
        {
                global $room_name, $room_id, $room_type;
                global $calaos, $input, $output;
?>
<div class="iPanel">
<h3><?php echo $room_name; ?></h3>

<fieldset>
<ul>
<?php
        for ($i = 0;$i < count($input);$i++)
        {
                $cont = false;
                $name = "";

                $res = explode(" ", $calaos->SendRequest("input ".$input[$i]." get"));
                for ($j = 1;$j < count($res);$j++)
                {
                        list($opt, $val) = explode(":", urldecode($res[$j]), 2);
                        if ($opt == "name")
                                $name = $val;
                        if ($opt == "type")
                        {
                                if ($val == "WITemp" || $val == "OWTemp") $temperature[] = $input[$i];
                                if ($val == "WIAnalog") $analog[] = $input[$i];
                                if ($val != "scenario")
                                {
                                        $cont = true;
                                        break;
                                }
                        }
                }

                if ($cont) continue;
?>
<li>
<table style="margin: 8px auto;">
<tr>
<td width="100%"><?php echo $name ?></td>
<td><a class="black button" style="width:30px" href="#" rev="async" onclick="input_action('<?php echo $input[$i]; ?>', 'true'); return false;">
        <img style="margin-top: 12px;" alt="go" src="img/icon_scenario.png" /></a></td>
</tr>
</table>
</li>
<?php
        }

        //Display temperature if any
        if (isset($temperature))
        {
                for ($i = 0;$i < count($temperature);$i++)
                {
                        $in["id"] = "";
                        $in["name"] = "";
                        $in["type"] = "";
                        $in["state"] = "0";

                        $res = explode(" ", $calaos->SendRequest("input ".$temperature[$i]." get"));
                        for ($j = 1;$j < count($res);$j++)
                        {
                                list($opt, $val) = explode(":", urldecode($res[$j]), 2);
                                $in[$opt] = $val;
                        }

                        $res = explode(" ", $calaos->SendRequest("input ".$temperature[$i]." state?"));
                        $in["state"] = urldecode($res[2]);
?>
<li>
<table style="margin: 8px auto;">
<tr>
<td><img style="width: 24px; height: 24px;" alt="temperature" src="../img/temp.png" /></td>
<td width="100%"><?php echo $in["name"]; ?></td>
<td><?php echo $in["state"]; ?>°C</td>
</tr>
</table>
</li>
<?php
                }
        }

        //Display temperature if any
        if (isset($analog))
        {
                for ($i = 0;$i < count($analog);$i++)
                {
                        $in["id"] = "";
                        $in["name"] = "";
                        $in["type"] = "";
                        $in["state"] = "0";
                        $in["unit"] = "";

                        $res = explode(" ", $calaos->SendRequest("input ".$analog[$i]." get"));
                        for ($j = 1;$j < count($res);$j++)
                        {
                                list($opt, $val) = explode(":", urldecode($res[$j]), 2);
                                $in[$opt] = $val;
                        }

                        $res = explode(" ", $calaos->SendRequest("input ".$analog[$i]." state?"));
                        $in["state"] = urldecode($res[2]);
?>
<li>
<table style="margin: 8px auto;">
<tr>
<td><img style="width: 24px; height: 24px;" alt="analog" src="../img/icon_analog.png" /></td>
<td width="100%"><?php echo $in["name"]; ?></td>
<td><?php echo $in["state"]." ".$in["unit"]; ?></td>
</tr>
</table>
</li>
<?php
                }
        }

        for ($i = 0;$i < count($output);$i++)
        {
                $in["id"] = "";
                $in["name"] = "";
                $in["type"] = "";
                $in["gtype"] = "";
                $in["state"] = "false";
                $in["visible"] = "false";
                $in["rw"] = "false";

                $res = explode(" ", $calaos->SendRequest("output ".$output[$i]." get"));
                for ($j = 1;$j < count($res);$j++)
                {
                        list($opt, $val) = explode(":", urldecode($res[$j]), 2);
                        $in[$opt] = $val;
                }

                //hide invisible elements
                if ($in["visible"] == "false") continue;

                //It's a light element
                if ($in["type"] == "WOAnalog")
                {
?>
<li>
<table style="margin: 8px auto;">
<tr>
<td width="100%">
<span style="text-align: left;" id="value_<?php echo $in["id"]; ?>"><?php echo $in["state"]." ".$in["unit"]; ?></span>
</td>
</tr>
</table>

<?php
                        if ($in["rw"] == "true")
                        {
?>
<table style="margin: 8px auto;">
<tr>
<td style="width:58px;"><a class="black button" style="width:30px" href="#" rev="async" onclick="analog_value_edit('<?php echo $in["id"]; ?>'); return false;">
        <img style="margin-top: 12px;" alt="go" src="img/icon_text.png" /></a></td>
</tr>
</table>
<?php
                        }
?>

</li>
<?php
                }

                //It's a light element
                if (($in["type"] == "WODigital" && $in["gtype"] == "light") ||
                    $in["type"] == "WODali" || $in["type"] == "WODaliRVB")
                {
                        if ($in["state"] == "true")
                                $img = "lighton";
                        else
                                $img = "lightoff";

                        $img_id = "img_".$in["id"];
?>
<li>
<table style="margin: 8px auto;">
<tr>
<td><img id="<?php echo $img_id; ?>" style="width: 24px; height: 24px;" alt="light" src="../img/<?php echo $img; ?>.png" /></td>
<td width="100%"><?php echo $in["name"]; ?></td>
</tr>
</table>

<table style="margin: 8px auto;">
<tr>
<td style="width:58px;"><a class="black button" style="width:30px" href="#" rev="async" onclick="output_action('<?php echo $in["id"]; ?>', 'true'); return false;">
        <img style="margin-top: 12px;" alt="go" src="img/icon_on.png" /></a></td>
<td style="width:58px;"><a class="black button" style="width:30px" href="#" rev="async" onclick="output_action('<?php echo $in["id"]; ?>', 'false'); return false;">
        <img style="margin-top: 12px;" alt="go" src="img/icon_off.png" /></a></td>
</tr>
</table>
</li>
<?php
                }

                //It's a tor element
                if (($in["type"] == "WODigital" && $in["gtype"] != "light"))
                {
                        if ($in["state"] == "true")
                                $img = "lighton";
                        else
                                $img = "lightoff";

                        $img_id = "img_".$in["id"];
?>
<li>
<table style="margin: 8px auto;">
<tr>
<td><img id="<?php echo $img_id; ?>" style="width: 24px; height: 24px;" alt="light" src="../img/<?php echo $img; ?>.png" /></td>
<td width="100%"><?php echo $in["name"]; ?></td>
</tr>
</table>

<table style="margin: 8px auto;">
<tr>
<td style="width:58px;"><a class="black button" style="width:30px" href="#" rev="async" onclick="output_action('<?php echo $in["id"]; ?>', 'true'); return false;">
        <img style="margin-top: 12px;" alt="go" src="img/icon_on.png" /></a></td>
<td style="width:58px;"><a class="black button" style="width:30px" href="#" rev="async" onclick="output_action('<?php echo $in["id"]; ?>', 'false'); return false;">
        <img style="margin-top: 12px;" alt="go" src="img/icon_off.png" /></a></td>
</tr>
</table>

</li>
<?php
                }

                //It's a volet element
                if ($in["type"] == "WOVolet" || $in["type"] == "WOVoletSmart")
                {
                        if ($in["type"] == "WOVolet")
                        {
                                if ($in["state"] == "true")
                                        $img = "icon_shutter_on";
                                else
                                        $img = "icon_shutter";
                        }
                        else if ($in["type"] == "WOVoletSmart")
                        {
                                list($vact, $vpos) = explode(" ", $in["state"], 2);
                                if ($vpos == "100")
                                        $img = "icon_shutter_on";
                                else
                                        $img = "icon_shutter";
                        }

                        $img_id = "img_".$in["id"];
?>
<li>
<table>
<tr>
<td><img style="width: 22px; height: 16px;" alt="light" src="../img/<?php echo $img; ?>.png" /></td>
<td width="100%"><?php echo $in["name"]; ?></td>
</tr>
</table>

<table style="margin: 8px auto;">
<tr>
        <td style="width:58px;"><a class="black button" style="width:30px" href="#" rev="async" onclick="output_action('<?php echo $in["id"]; ?>', 'up'); return false;">
                <img style="margin-top: 12px;" alt="up" src="img/icon_up.png" /></a></td>
        <td style="width:58px;" ><a class="black button" style="width:30px" href="#" rev="async" onclick="output_action('<?php echo $in["id"]; ?>', 'stop'); return false;">
                <img style="margin-top: 12px;" alt="stop" src="img/icon_stop2.png" /></a></td>
        <td style="width:58px;"><a class="black button" style="width:30px" href="#" rev="async" onclick="output_action('<?php echo $in["id"]; ?>', 'down'); return false;">
                <img style="margin-top: 12px;" alt="down" src="img/icon_down.png" /></a></td>
</tr>
</table>

</li>
<?php
                }

                //It's an Internal Value (BOOL)
                if ($in["type"] == "InternalBool")
                {
                        if ($in["state"] == "true")
                                $img = "checkon";
                        else
                                $img = "checkoff";

                        $img_id = "img_".$in["id"];
?>
<li>
<table style="margin: 8px auto;">
<tr>
<td><img id="<?php echo $img_id; ?>" style="width: 24px; height: 24px;" alt="light" src="../img/<?php echo $img; ?>.png" /></td>
<td width="100%"><?php echo $in["name"]; ?></td>
</tr>
</table>

<?php
                        if ($in["rw"] == "true")
                        {
?>
<table style="margin: 8px auto;">
<tr>
<td style="width:58px;"><a class="black button" style="width:30px" href="#" rev="async" onclick="output_action('<?php echo $in["id"]; ?>', 'true'); return false;">
        <img style="margin-top: 12px;" alt="go" src="img/icon_on.png" /></a></td>
<td style="width:58px;"><a class="black button" style="width:30px" href="#" rev="async" onclick="output_action('<?php echo $in["id"]; ?>', 'false'); return false;">
        <img style="margin-top: 12px;" alt="go" src="img/icon_off.png" /></a></td>
</tr>
</table>
<?php
                        }
?>

</li>
<?php
                }

                //It's an Internal Value (INT)
                if ($in["type"] == "InternalInt")
                {
?>
<li>
<table style="margin: 8px auto;">
<tr>
<td><b><span id="value_<?php echo $in["id"]; ?>"><?php echo $in["state"]; ?></span></b></td>
<td width="100%"><?php echo $in["name"]; ?></td>
</tr>
</table>

<?php
                        if ($in["rw"] == "true")
                        {
?>
<table style="margin: 8px auto;">
<tr>
<td style="width:58px;"><a class="black button" style="width:30px" href="#" rev="async" onclick="int_value_increase('<?php echo $in["id"]; ?>'); return false;">
        <img style="margin-top: 12px;" alt="go" src="img/icon_plus.png" /></a></td>
<td style="width:58px;"><a class="black button" style="width:30px" href="#" rev="async" onclick="int_value_decrease('<?php echo $in["id"]; ?>'); return false;">
        <img style="margin-top: 12px;" alt="go" src="img/icon_moins.png" /></a></td>
</tr>
</table>
<?php
                        }
?>

</li>
<?php
                }

                //It's an Internal Value (STRING)
                if ($in["type"] == "InternalString")
                {
                        if ($in["state"] == "")
                                $in["state"] = $in["name"];
?>
<li>
<table style="margin: 8px auto;">
<tr>
<td width="100%">
<span style="text-align: left;" id="value_<?php echo $in["id"]; ?>"><?php echo $in["state"]; ?></span>
</td>
</tr>
</table>

<?php
                        if ($in["rw"] == "true")
                        {
?>
<table style="margin: 8px auto;">
<tr>
<td style="width:58px;"><a class="black button" style="width:30px" href="#" rev="async" onclick="string_value_edit('<?php echo $in["id"]; ?>'); return false;">
        <img style="margin-top: 12px;" alt="go" src="img/icon_text.png" /></a></td>
</tr>
</table>
<?php
                        }
?>

</li>
<?php
                }
        }
?>

</ul>
</fieldset>

</div>
<?php
        }
        $calaos->Clean();
?>
