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
<h1 class="list_header">Actions de la pièce :</h1>
<div class="list_scenario">
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
<a href="#" onclick="input_action('<?php echo $input[$i] ?>','true'); return true;" class="sub_scenario"><?php echo $name ?></a>
<?php
        }
?>
</div>
<table width=100% style="border: 1px;margin-top:1em;" border="0">
<?php
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
<tr class="list_element">
<td style="text-align: center; width: 30px;">
<img style="width: 24px; height: 24px;" alt="temperature" src="img/temp.png" /></td>
<td style="width: 80%;"><?php echo $in["name"]; ?></td>
<td><em><?php echo $in["state"]; ?>°C</em></td>
</tr>
<?php
                }
        }

        //Display analog if any
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
<tr class="list_element">
<td style="text-align: center; width: 30px;">
<img style="width: 24px; height: 24px;" alt="temperature" src="img/icon_analog.png" /></td>
<td style="width: 80%;"><?php echo $in["name"]; ?></td>
<td><em><?php echo $in["state"]." ".$in["unit"]; ?></em></td>
</tr>
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
                $in["unit"] = "";

                $res = explode(" ", $calaos->SendRequest("output ".$output[$i]." get"));
                for ($j = 1;$j < count($res);$j++)
                {
                        list($opt, $val) = explode(":", urldecode($res[$j]), 2);
                        $in[$opt] = $val;
                }

                //hide invisible elements
                if ($in["visible"] == "false") continue;

                //It's an analog element
                if ($in["type"] == "WOAnalog")
                {
?>
<tr class="list_element">
<td style="text-align: center; width: 30px;">
<img style="width: 24px; height: 24px;" alt="analog" src="img/icon_analog.png" /></td>
<td style="width: 80%;"><?php echo $in["name"]; ?></td>
<td>
<span id="value_<?php echo $in["id"]; ?>"><?php echo $in["state"]." ".$in["unit"]; ?></span>
</td>
<td>
<?php
                        if ($in["rw"] == "true")
                        {
?>
<button dojoType="Button" onclick="analog_value_edit('<?php echo $in["id"]; ?>'); return true;">
<div class="inside_button">Changer</div></button>
<?php
                        }
?>
</td>
</tr>
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
<tr class="list_element">
<td style="text-align: center; width: 30px;">
<img id="<?php echo $img_id; ?>" style="width: 24px; height: 24px;" alt="light" src="img/<?php echo $img; ?>.png" /></td>
<td style="width: 80%;"><?php echo $in["name"]; ?></td>
<td><table width="100%" border="0"><tbody><tr>
<td><button dojoType="Button" onclick="output_action('<?php echo $in["id"]; ?>','true'); return true;">
<div class="inside_button">Allumer</div></button></td>
<td><button dojoType="Button" onclick="output_action('<?php echo $in["id"]; ?>','false'); return true;">
<div class="inside_button">Eteindre</div></button></td>
</tr></tbody></table></td>
</tr>
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
<tr class="list_element">
<td style="text-align: center; width: 30px;">
<img id="<?php echo $img_id; ?>" style="width: 24px; height: 24px;" alt="light" src="img/<?php echo $img; ?>.png" /></td>
<td style="width: 80%;"><?php echo $in["name"]; ?></td>
<td><table width="100%" border="0"><tbody><tr>
<td><button dojoType="Button" onclick="output_action('<?php echo $in["id"]; ?>','impulse 200'); return true;">
<div class="inside_button">Impulsion</div></button></td>
<td><button dojoType="Button" onclick="output_action('<?php echo $in["id"]; ?>','true'); return true;">
<div class="inside_button">Allumer</div></button></td>
<td><button dojoType="Button" onclick="output_action('<?php echo $in["id"]; ?>','false'); return true;">
<div class="inside_button">Eteindre</div></button></td>
</tr></tbody></table></td>
</tr>
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
<tr class="list_element">
<td style="text-align: center; width: 30px;">
<img style="width: 22px; height: 16px;" alt="light" src="img/<?php echo $img; ?>.png" /></td>
<td style="width: 80%;"><?php echo $in["name"]; ?></td>
<td><table width="100%" border="0"><tbody><tr>
<td><button dojoType="Button" onclick="output_action('<?php echo $in["id"]; ?>','up'); return true;">
<div class="inside_button">Monter</div></button></td>
<td><button dojoType="Button" onclick="output_action('<?php echo $in["id"]; ?>','down'); return true;">
<div class="inside_button">Descendre</div></button></td>
<td><button dojoType="Button" onclick="output_action('<?php echo $in["id"]; ?>','stop'); return true;">
<div class="inside_button">Arreter</div></button></td>
<?php
                        if ($in["type"] == "WOVoletSmart")
                        {
?>
<td><button dojoType="Button" onclick="output_action('<?php echo $in["id"]; ?>','calibrate'); return true;">
<div class="inside_button">Calibrer</div></button></td>
<?php
                        }
?>
</tr></tbody></table></td>
</tr>
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
<tr class="list_element">
<td style="text-align: center; width: 30px;">
<img id="<?php echo $img_id; ?>" style="width: 24px; height: 24px;" alt="light" src="img/<?php echo $img; ?>.png" /></td>
<td style="width: 80%;"><?php echo $in["name"]; ?></td>
<td>
<?php
                        if ($in["rw"] == "true")
                        {
?>
<table width="100%" border="0"><tbody><tr>
<td><button dojoType="Button" onclick="output_action('<?php echo $in["id"]; ?>','true'); return true;">
<div class="inside_button">Activer</div></button></td>
<td><button dojoType="Button" onclick="output_action('<?php echo $in["id"]; ?>','false'); return true;">
<div class="inside_button">D&eacute;sactiver</div></button></td>
</tr></tbody></table>
<?php
                        }
?>
</td>
</tr>
<?php
                }

                //It's an Internal Value (INT)
                if ($in["type"] == "InternalInt")
                {
?>
<tr class="list_element">
<td style="text-align: center; width: 30px;">
<b><span id="value_<?php echo $in["id"]; ?>"><?php echo $in["state"]; ?></span></b>
</td>
<td style="width: 80%;"><?php echo $in["name"]; ?></td>
<td>
<?php
                        if ($in["rw"] == "true")
                        {
?>
<table width="100%" border="0"><tbody><tr>
<td><button dojoType="Button" onclick="int_value_increase('<?php echo $in["id"]; ?>'); return true;">
<div class="inside_button">Plus</div></button></td>
<td><button dojoType="Button" onclick="int_value_decrease('<?php echo $in["id"]; ?>'); return true;">
<div class="inside_button">Moins</div></button></td>
</tr></tbody></table>
<?php
                        }
?>
</td>
</tr>
<?php
                }

                //It's an Internal Value (STRING)
                if ($in["type"] == "InternalString")
                {
                        if ($in["state"] == "")
                                $in["state"] = $in["name"];
?>
<tr class="list_element">
<td style="text-align: center; width: 30px;">
<img style="width: 24px; height: 24px;" alt="light" src="img/text.png" />
</td>
<td style="width: 80%;">
<span id="value_<?php echo $in["id"]; ?>"><?php echo $in["state"]; ?></span>
</td>
<td>
<?php
                        if ($in["rw"] == "true")
                        {
?>
<table width="100%" border="0"><tbody><tr>
<td><button dojoType="Button" onclick="string_value_edit('<?php echo $in["id"]; ?>'); return true;">
<div class="inside_button">Editer</div></button></td>
</tr></tbody></table>
<?php
                        }
?>
</td>
</tr>
<?php
                }

        }
?>
</table>
