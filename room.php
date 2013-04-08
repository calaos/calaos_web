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
<div id="container">
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
                                echo "$val ";
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
<div id="container" class="calaos-block" >
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
<div>
<img style="width: 24px; height: 24px;" alt="temperature" src="assets/img/temp.png"/><?php echo $in["name"]; ?><em>  : <?php echo $in["state"]; ?>°C</em>
</div>
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
<div>
<img style="width: 24px; height: 24px;" alt="temperature" src="assets/img/icon_analog.png" /></td>
<em><?php echo $in["state"]." ".$in["unit"]; ?></em>
</div>
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
<div>
<img style="width: 24px; height: 24px;" alt="analog" src="assets/img/icon_analog.png" /></td>
<?php echo $in["name"]; ?>
<span id="value_<?php echo $in["id"]; ?>"><?php echo $in["state"]." ".$in["unit"]; ?></span>
</div>

<?php
                        if ($in["rw"] == "true")
                        {
?>
<button class="btn" type="button" onclick="analog_value_edit('<?php echo $in["id"]; ?>'); return true;">Changer</button>
<?php
                        }
?>

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
<div>
<img id="<?php echo $img_id; ?>" style="width: 24px; height: 24px;" alt="light" src="assets/img/<?php echo $img; ?>.png" />
<?php echo $in["name"]; ?>

<button class="btn btn-block" type="button" onclick="analog_value_edit('<?php echo $in["id"]; ?>','true'); return true;">Allumer</button>
<button class="btn btn-block" type="button" onclick="analog_value_edit('<?php echo $in["id"]; ?>','false'); return true;">Eteindre</button>
</div>
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
<div>
<img id="<?php echo $img_id; ?>" style="width: 24px; height: 24px;" alt="light" src="assets/img/<?php echo $img; ?>.png" />
<?php echo $in["name"]; ?>
<button dojoType="Button" onclick="output_action('<?php echo $in["id"]; ?>','impulse 200'); return true;">
<div class="inside_button">Impulsion</div></button>
<button class="btn btn_block" type="button" onclick="analog_value_edit('<?php echo $in["id"]; ?>','true'); return true;">Allumer</button>
<button class="btn btn_block" type="button" onclick="analog_value_edit('<?php echo $in["id"]; ?>','false'); return true;">Eteindre</button>
</div>
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
<div>
<img style="width: 22px; height: 16px;" alt="light" src="assets/img/<?php echo $img; ?>.png" /></td>
<?php echo $in["name"]; ?>

<button dojoType="Button" onclick="output_action('<?php echo $in["id"]; ?>','up'); return true;">
<div class="inside_button">Monter</div></button>
<button dojoType="Button" onclick="output_action('<?php echo $in["id"]; ?>','down'); return true;">
<div class="inside_button">Descendre</div></button>
<button dojoType="Button" onclick="output_action('<?php echo $in["id"]; ?>','stop'); return true;">
<div class="inside_button">Arreter</div></button>
</div>
<?php
                        if ($in["type"] == "WOVoletSmart")
                        {
?>
<button dojoType="Button" onclick="output_action('<?php echo $in["id"]; ?>','calibrate'); return true;">
<div class="inside_button">Calibrer</div></button>
<?php
                        }
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
<div>
<img id="<?php echo $img_id; ?>" style="width: 24px; height: 24px;" alt="light" src="assets/img/<?php echo $img; ?>.png" />
<?php echo $in["name"]; ?>
</div>
<?php
                        if ($in["rw"] == "true")
                        {
?>
<div>
<button class="btn btn-block" type="button" onclick="analog_value_edit('<?php echo $in["id"]; ?>','true'); return true;">Activer</button>
<button class="btn btn-block" type="button" onclick="analog_value_edit('<?php echo $in["id"]; ?>','false'); return true;">Désactiver</button>
</div>
<?php
                        }
                }

                //It's an Internal Value (INT)
                if ($in["type"] == "InternalInt")
                {
?>
<div>
<b><span id="value_<?php echo $in["id"]; ?>"><?php echo $in["state"]; ?></span></b>
<?php echo $in["name"]; ?>
<?php
                        if ($in["rw"] == "true")
                        {
?>

<button class="btn btn-block" type="button" onclick="analog_value_edit('<?php echo $in["id"]; ?>','true'); return true;">Plus</button>
<button class="btn btn-block" type="button" onclick="analog_value_edit('<?php echo $in["id"]; ?>','false'); return true;">Moins</button>
</div>
<?php
                        }
                }

                //It's an Internal Value (STRING)
                if ($in["type"] == "InternalString")
                {
                        if ($in["state"] == "")
                                $in["state"] = $in["name"];
?>
<div class="list_element">
<img style="width: 24px; height: 24px;" alt="light" src="assets/img/text.png" />
<span id="value_<?php echo $in["id"]; ?>"><?php echo $in["state"]; ?></span>
<?php
                        if ($in["rw"] == "true")
                        {
?>
<button dojoType="Button" onclick="string_value_edit('<?php echo $in["id"]; ?>'); return true;">
<div class="inside_button">Editer</div></button>
</div>
<?php
                        }
                }

        }
?>


<input type="text" value="18" class="dial" data-fgColor="#66CC66" data-angleOffset=-125 data-angleArc=250 data-step="0.5" data-min="0" data-max="35">

<script>
$(function() {
    $(".dial").knob();
});
</script>