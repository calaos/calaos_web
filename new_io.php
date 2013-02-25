<?php
        //Check user identity
        require "auth.php";

        require_once "Calaos.php";

        $calaos = Calaos::Instance();

        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

        $radio = @$_GET['radio'];
        $room_id = @$_GET['room_id'];
        if (!isset($room_id))
                die ("Error: room_id not set...");
        $room_type = @$_GET['room_type'];
        if (!isset($room_type))
                die ("Error: room_type not set...");
        $room_name = @urldecode($_GET['room_name']);

        if (!isset($radio))
        {
?>
<div style="text-align: left;width: 400px; padding: 5px;">
<h1 class="list_header">Choisir le type :</h1>
<form name="create_form">
  <table style="text-align: left; width: 100%;" border="0"
 cellpadding="2" cellspacing="2">
    <tbody>
      <tr>
        <td>
        <input name="radio" value="wago" type="radio">Entr&eacute;e/Sortie
automate
        <br>
        <input name="radio" value="camera" type="radio">Cam&eacute;ra
        <br>
        <input name="radio" value="irmodule" type="radio">Module
Infrarouge <br>
        <input name="radio" value="audio" type="radio">Lecteur de musique
        <br>
        <input value="internal" name="radio" type="radio">Variable
interne <br>
        <input value="x10" name="radio" type="radio">Sortie
X10
        <br>
        <input value="horaire" name="radio" type="radio">Variable
horaire <br>
        <input value="plage" name="radio" type="radio">Plage
horaire
        </td>
      </tr>
      <tr>
        <td style="float: right;">
<button dojoType="Button" onclick="CreateState(2,'none',<?php echo "'$room_type',$room_id,'".urldecode($room_name)."'"; ?>); return true;">
<div class="inside_button">Cr&eacute;er</div></button>
        </td>
      </tr>
    </tbody>
  </table>
</form>
</div>
<?php
        }
        else if ($radio == "wago")
        {
?>
<div style="text-align: left;width: 300px; padding: 5px;">
<h1 class="list_header">Choisir le type :</h1>
<form name="create_form">
  <table style="text-align: left; width: 100%;" border="0"
 cellpadding="2" cellspacing="2">
    <tbody>
      <tr>
        <td>
        <input name="radio" value="WIDigital" type="radio">Entr&eacute;e Bouton direct
        <br>
        <input name="radio" value="WIDigitalBP" type="radio">Entr&eacute;e Bouton poussoir
        <br>
        <input name="radio" value="WIDigitalTriple" type="radio">Entr&eacute;e Bouton poussoir (3 actions)
        <br>
        <input value="WITemp" name="radio" type="radio">Sonde de temp&eacute;rature
	<input value="OWTemp" name="radio" type="radio">Sonde de temp&eacute;rature
        <br>
        <input value="WODigital" name="radio" type="radio">Sortie automate TOR
        <br>
        <input value="WODigitalLight" name="radio" type="radio">Sortie Lumi&egrave;re
        <br>
        <input value="WOVolet" name="radio" type="radio">Sortie Volet
        <br>
        <input value="WOVoletSmart" name="radio" type="radio">Sortie Volet Intelligent
        <br>
        <input value="WONeon" name="radio" type="radio">Sortie Neon (0-10V) <em>(obsolete)</em>
        <br>
        <input value="WODali" name="radio" type="radio">Sortie Dali
        <br>
        <input value="WODaliRVB" name="radio" type="radio">Sortie Dali RGB
        </td>
      </tr>
      <tr>
        <td style="float: right;">
<button dojoType="Button" onclick="CreateState(2,'<?php echo $radio; ?>',<?php echo "'$room_type',$room_id,'".urldecode($room_name)."'"; ?>); return true;">
<div class="inside_button">Cr&eacute;er</div></button>
        </td>
      </tr>
    </tbody>
  </table>
</form>
</div>
<?php
        }
        else if ($radio == "camera")
        {
?>
<div style="text-align: left;width: 300px; padding: 5px;">
<h1 class="list_header">Param&egrave;tres de la cam&eacute;ra :</h1>
<form name="create_form">
  <table style="text-align: left; width: 100%;" border="0"
 cellpadding="2" cellspacing="2">
    <tbody>
      <tr>
        <td>
        <input name="radio" value="gadspot" type="radio" checked onclick="_cam_show('cam_gadspot')">Gadspot
        <br>
        <input name="radio" value="axis" type="radio" onclick="_cam_show('cam_axis')">Axis
        <br>
        <input name="radio" value="planet" type="radio" onclick="_cam_show('cam_planet')">Planet
        <br>
        <input name="radio" value="standard_mjpeg" type="radio" onclick="_cam_show('cam_mjpeg')">Standard (Mjpeg)
        </td></tr>
        <tr><td>
        <div id="cam_gadspot" style="display:block;">
                <table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2"><tbody>
                <tr><td><label>Nom de la cam&eacute;ra: </label></td><td><input name="name_gadspot" value="Camera"></td></tr>
                <tr><td><label>Adresse IP: </label></td><td><input name="host_gadspot" value="192.168.0.10"></td></tr>
                <tr><td><label>Port: </label></td><td><input name="port_gadspot" value="80"></td></tr>
                </tbody></table>
        </div>
        <div id="cam_axis" style="display:none;">
                <table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2"><tbody>
                <tr><td><label>Nom de la cam&eacute;ra: </label></td><td><input name="name_axis" value="Camera"></td></tr>
                <tr><td><label>Adresse IP: </label></td><td><input name="host_axis" value="192.168.0.10"></td></tr>
                <tr><td><label>Numero de camera (<em>facultatif, peut prendre la valeur 1, 2, 3, 4 ou quad</em>): </label></td><td><input name="model_axis" value=""></td></tr>
                <tr><td><label>Port: </label></td><td><input name="port_axis" value="80"></td></tr>
                </tbody></table>
        </div>
        <div id="cam_planet" style="display:none;">
                <table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2"><tbody>
                <tr><td><label>Nom de la cam&eacute;ra: </label></td><td><input name="name_planet" value="Camera"></td></tr>
                <tr><td><label>Adresse IP: </label></td><td><input name="host_planet" value="192.168.0.10"></td></tr>
                <tr><td><label>Modele: </label>
                        </td><td>
                                <select name="model_planet">
                                        <option value="ICA-300" selected>ICA-300</option>
                                        <option value="ICA-302" selected>ICA-302</option>
                                        <option value="ICA-500" selected>ICA-500</option>
                                        <option value="ICA-210" selected>ICA-210</option>
                                        <option value="ICA-210W" selected>ICA-210W</option>
                                </select>
                        </td></tr>
                <tr><td><label>Port: </label></td><td><input name="port_planet" value="80"></td></tr>
                </tbody></table>
        </div>
        <div id="cam_mjpeg" style="display:none;">
                <table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2"><tbody>
                <tr><td><label>Nom de la cam&eacute;ra: </label></td><td><input name="name_mjpeg" value="Camera"></td></tr>
                <tr><td><label>Adresse IP: </label></td><td><input name="host_mjpeg" value="192.168.0.10"></td></tr>
                <tr><td><label>Url (jpeg): </label></td><td><input name="url_jpeg" value=""></td></tr>
                <tr><td><label>Url (mjpeg): </label></td><td><input name="url_mjpeg" value=""></td></tr>
                <tr><td><label>Url (mpeg4): </label></td><td><input name="url_mpeg" value=""></td></tr>
                <tr><td><label>Port: </label></td><td><input name="port_mjpeg" value="80"></td></tr>
                </tbody></table>
        </div>
        </td></tr>
      <tr>
        <td style="float: right;">
<button dojoType="Button" onclick="CreateState(3,'camera_param',<?php echo "'$room_type',$room_id,'".urldecode($room_name)."'"; ?>); return true;">
<div class="inside_button">Cr&eacute;er</div></button>
        </td>
      </tr>
    </tbody>
  </table>
</form>
</div>
<?php
        }
        else if ($radio == "irmodule")
        {
?>
<div style="text-align: left;width: 300px; padding: 5px;">
<h1 class="list_header">Param&egrave;tres du module IR :</h1>
<form name="create_form">
  <table style="text-align: left; width: 100%;" border="0"
 cellpadding="2" cellspacing="2">
    <tbody>
      <tr>
        <td>
        <input name="radio" value="irtrans" type="radio" checked>IRTrans
        </td></tr>
        <tr><td>
        <table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2"><tbody>
        <tr><tr><td><label>Nom du module: </label></td><td><input name="name" value="Module IR"></td></tr>
        <tr><tr><td><label>Adresse IP: </label></td><td><input name="host" value="192.168.0.10"></td></tr>
        <tr><tr><td><label>Port: </label></td><td><input name="port" value="21000"></td></tr>
        </tbody></table>
        </td></tr>
      <tr>
        <td style="float: right;">
<button dojoType="Button" onclick="CreateState(3,'irmodule_param',<?php echo "'$room_type',$room_id,'".urldecode($room_name)."'"; ?>); return true;">
<div class="inside_button">Cr&eacute;er</div></button>
        </td>
      </tr>
    </tbody>
  </table>
</form>
</div>
<?php
        }
        else if ($radio == "internal")
        {
?>
<div style="text-align: left;width: 300px; padding: 5px;">
<h1 class="list_header">Param&egrave;tres de la variable :</h1>
<form name="create_form">
  <table style="text-align: left; width: 100%;" border="0"
 cellpadding="2" cellspacing="2">
    <tbody>
      <tr>
        <td>
        <input name="radio" value="scenario" type="radio" checked>Entr&eacute;e sc&eacute;nario
        <br>
        <input name="radio" value="InternalInt" type="radio">Variable num&eacute;rique
        <br>
        <input name="radio" value="InternalBool" type="radio">Variable bool&eacute;enne
        <br>
        <input name="radio" value="InternalString" type="radio">Variable texte
        </td></tr>
        <tr><td>
        <table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2"><tbody>
        <tr>
        <tr><td><label>Nom de la variable: </label></td><td><input name="name" value="Variable"></td></tr>
        </tbody></table>
        </td></tr>
      <tr>
        <td style="float: right;">
<button dojoType="Button" onclick="CreateState(3,'intern_param',<?php echo "'$room_type',$room_id,'".urldecode($room_name)."'"; ?>); return true;">
<div class="inside_button">Cr&eacute;er</div></button>
        </td>
      </tr>
    </tbody>
  </table>
</form>
</div>
<?php
        }
        else if ($radio == "x10")
        {
?>
<div style="text-align: left;width: 300px; padding: 5px;">
<h1 class="list_header">Param&egrave;tres du module X10 :</h1>
<form name="create_form">
  <table style="text-align: left; width: 100%;" border="0"
 cellpadding="2" cellspacing="2">
    <tbody>
      <tr>
        <td>
        <input name="radio" value="X10Output" type="radio" checked>Sortie X10 variateur
        </td></tr>
        <tr><td>
        <table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2"><tbody>
        <tr><tr><td><label>Nom de la sortie X10: </label></td><td><input name="name" value="Sortie X10"></td></tr>
        <tr><tr><td><label>Code maison: </label></td><td><input name="code" value="A1"></td></tr>
        </tbody></table>
        </td></tr>
      <tr>
        <td style="float: right;">
<button dojoType="Button" onclick="CreateState(3,'x10_param',<?php echo "'$room_type',$room_id,'".urldecode($room_name)."'"; ?>); return true;">
<div class="inside_button">Cr&eacute;er</div></button>
        </td>
      </tr>
    </tbody>
  </table>
</form>
</div>
<?php
        }
        else if ($radio == "audio")
        {
?>
<div style="text-align: left;width: 300px; padding: 5px;">
<h1 class="list_header">Param&egrave;tres du lecteur audio :</h1>
<form name="create_form">
  <table style="text-align: left; width: 100%;" border="0"
 cellpadding="2" cellspacing="2">
    <tbody>
      <tr>
        <td>
        <input name="radio" value="slim" type="radio" checked>Squeezebox
        </td></tr>
        <tr><td>
        <table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2"><tbody>
        <tr><td><label>Nom du lecteur: </label></td><td><input name="name" value="Lecteur audio"></td></tr>
        <tr><td><label>Adresse IP (Slimserver): </label></td><td><input name="host" value="192.168.0.10"></td></tr>
        <tr><td><label>Port: </label></td><td><input name="port" value="9090"></td></tr>
        <tr><td><label>Adresse Mac (Squeezebox): </label></td><td><input name="mac" value=""></td></tr>
        </tbody></table>
        </td></tr>
      <tr>
        <td style="float: right;">
<button dojoType="Button" onclick="CreateState(3,'audio_param',<?php echo "'$room_type',$room_id,'".urldecode($room_name)."'"; ?>); return true;">
<div class="inside_button">Cr&eacute;er</div></button>
        </td>
      </tr>
    </tbody>
  </table>
</form>
</div>
<?php
        }
        else if ($radio == "horaire")
        {
?>
<div style="text-align: left;width: 300px; padding: 5px;">
<h1 class="list_header">Param&egrave;tres de l'heure :</h1>
<form name="create_form">
  <table style="text-align: left; width: 100%;" border="0"
 cellpadding="2" cellspacing="2">
    <tbody>
        <tr>
        <td>
        <input name="radio" value="InputTime" type="radio" checked>Heure
        <br>
        <input name="radio" value="InputTimeDate" type="radio">Date et Heure
        <br>
        <input name="radio" value="InputTimer" type="radio">Timer (tempo)
        </td></tr>
        <tr><td>
        <table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2"><tbody>
        <tr><td><label>Nom de l'heure: </label></td><td><input name="name" value="Temps"></td></tr>
        <tr><td><label>Heure: </label></td><td><input name="hour" value="0"></td></tr>
        <tr><td><label>Minute: </label></td><td><input name="min" value="0"></td></tr>
        <tr><td><label>Seconde: </label></td><td><input name="sec" value="0"></td></tr>
        <tr><td><label>ms (pour le timer): </label></td><td><input name="msec" value="0"></td></tr>
        </tbody></table>
        Uniquement pour <em>Date et Heure</em> :
        <table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2"><tbody>
        <tr><td><label>Ann&eacute;e: </label></td><td><input name="year" value="0"></td></tr>
        <tr><td><label>Mois: </label></td><td><input name="month" value="0"></td></tr>
        <tr><td><label>Jour: </label></td><td><input name="day" value="0"></td></tr>
        </tbody></table>
        </td></tr>
      <tr>
        <td style="float: right;">
<button dojoType="Button" onclick="CreateState(3,'horaire_param',<?php echo "'$room_type',$room_id,'".urldecode($room_name)."'"; ?>); return true;">
<div class="inside_button">Cr&eacute;er</div></button>
        </td>
      </tr>
    </tbody>
  </table>
</form>
</div>
<?php
        }
        else if ($radio == "plage")
        {
?>
<div style="text-align: left;width: 300px; padding: 5px;">
<h1 class="list_header">Param&egrave;tres de la plage horaire :</h1>
<form name="create_form">
  <table style="text-align: left; width: 100%;" border="0"
 cellpadding="2" cellspacing="2">
    <tbody>
        <tr>
        <td>
        <input name="radio" value="InPlageHoraire" type="radio" checked>Plage horaire
        </td></tr>
        <tr><td>
        <table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2"><tbody>
        <tr><td><label>Nom de la plage: </label></td><td><input name="name" value="Plage horaire"></td></tr>
        </tbody></table>
        </td></tr>
      <tr>
        <td style="float: right;">
<button dojoType="Button" onclick="CreateState(3,'plage_param',<?php echo "'$room_type',$room_id,'".urldecode($room_name)."'"; ?>); return true;">
<div class="inside_button">Cr&eacute;er</div></button>
        </td>
      </tr>
    </tbody>
  </table>
</form>
</div>
<?php
        }
        else if ($radio == "WIDigital" || $radio == "WIDigitalBP" || $radio == "WIDigitalTriple" ||
                 $radio == "WITemp" || $radio == "WODigital" || $radio == "WODigitalLight" ||
                 $radio == "scenario" || $radio == "WOVolet" || $radio == "WOVoletSmart" || $radio == "WONeon" ||
		 $radio == "OWTemp")
         {

                switch ($radio)
                {
                  case "WIDigital": $_name = "Param&egrave;tres de l'entr&eacute;e Bouton Direct"; break;
                  case "WIDigitalBP": $_name = "Param&egrave;tres de l'entr&eacute;e Bouton poussoir"; break;
                  case "WIDigitalTriple": $_name = "Param&egrave;tres de l'entr&eacute;e Bouton poussoir (3 actions)"; break;
                  case "WITemp": $_name = "Param&egrave;tres de la sonde temp&eacute;rature"; break;
  		  case "OWTemp": $_name = "Param&egrave;tres de la sonde temp&eacute;rature"; break;
                  case "WODigital": $_name = "Param&egrave;tres de la sortie TOR"; break;
                  case "WODigitalLight": $_name = "Param&egrave;tres de la sortie Lumi&egrave;re"; break;
                  case "scenario": $_name = "Param&egrave;tres de l'entr&eacute;e Sc&eacute;nario"; break;
                  case "WOVolet": $_name = "Param&egrave;tres de la sortie Volet"; break;
                  case "WOVoletSmart": $_name = "Param&egrave;tres de la sortie Volet Intelligent"; break;
                  case "WONeon": $_name = "Param&egrave;tres de la sortie Neon (0-10V)"; break;
                  default: break;
                }
?>
<div style="text-align: left;width: 300px; padding: 5px;">
<h1 class="list_header"><?php echo $_name; ?> :</h1>
<form name="create_form">
  <table style="text-align: left; width: 100%;" border="0"
 cellpadding="2" cellspacing="2">
    <tbody>
        <tr><td>
        <input name="radio" value="<?php echo $radio; ?>" type="hidden" checked>
        <table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2"><tbody>
                <tr><td><label>Nom : </label></td><td><input name="name" value=""></td></tr>
                <?php if ($radio != "WOVolet" && $radio != "WOVoletSmart" && $radio != "scenario") { ?>
                <tr><td><label>Var (WAGO) : </label></td><td><input name="_var" value="0"></td></tr>
                <?php } if ($radio == "WOVolet" ||  $radio == "WOVoletSmart") { ?>
                <tr><td><label>Var mont&eacute;e (WAGO) : </label></td><td><input name="var_up" value="0"></td></tr>
                <tr><td><label>Var descente (WAGO) : </label></td><td><input name="var_down" value="0"></td></tr>
                <?php           if ($radio == "WOVolet") { ?>
                <tr><td><label>Dur&eacute;e (sec.) : </label></td><td><input name="time" value="30"></td></tr>
                <?php           } else if ($radio == "WOVoletSmart") { ?>
                <tr><td><label>Dur&eacute;e mont&eacute;e (sec.) : </label></td><td><input name="time_up" value="30"></td></tr>
                <tr><td><label>Dur&eacute;e descente (sec.) : </label></td><td><input name="time_down" value="28"></td></tr>
                <tr><td><label>Var Sauvegarde (WAGO) [0-100] : </label></td><td><input name="var_save" value=""></td></tr>
                <tr><td><label>Impulsion (msec.) (<em>facultatif</em>) : </label></td><td><input name="impulse_time" value=""></td></tr>
                <?php } } else if ($radio == "WONeon") { ?>
                <tr><td><label>Var relais (WAGO) : </label></td><td><input name="var_relay" value="0"></td></tr>
                <?php } ?>
        </tbody></table>
        </td></tr>
      <tr>
        <td style="float: right;">
<button dojoType="Button" onclick="CreateState(3,'<?php echo $radio; ?>',<?php echo "'$room_type',$room_id,'".urldecode($room_name)."'"; ?>); return true;">
<div class="inside_button">Cr&eacute;er</div></button>
        </td>
      </tr>
    </tbody>
  </table>
</form>
</div>
<?php
        }
                else if ($radio == "WODali" || $radio == "WODaliRVB")
         {

                if ($radio == "WODali") $_name = "Param&egrave;tres de la sortie DALI";
                if ($radio == "WODaliRVB") $_name = "Param&egrave;tres de la sortie DALI RGB (3 sorties)";
?>
<div style="text-align: left;width: 300px; padding: 5px;">
<h1 class="list_header"><?php echo $_name; ?> :</h1>
<form name="create_form">
  <table style="text-align: left; width: 100%;" border="0"
 cellpadding="2" cellspacing="2">
    <tbody>
        <tr><td>
        <input name="radio" value="<?php echo $radio; ?>" type="hidden" checked>
        <table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2"><tbody>
                <tr><td><label>Nom : </label></td><td><input name="name" value=""></td></tr>
<?php if ($radio == "WODali") { ?>
                <tr><td><label>Module DALI : </label></td><td><input name="line" value="1"></td></tr>
                <tr><td><label>Adresse de groupe? : </label></td><td><input type="checkbox" name="group" value="true"></td></tr>
                <tr><td><label>Adresse DALI : </label></td><td><input name="address" value="0"></td></tr>
                <tr><td><label>Dur&eacute;e de transition : </label></td><td><input name="fade_time" value="1"></td></tr>
<?php } else { ?>
                <tr><td><b><em>Couleur rouge:</em></b></tr></td>
                <tr><td><label>Module DALI : </label></td><td><input name="rline" value="1"></td></tr>
                <tr><td><label>Adresse de groupe? : </label></td><td><input type="checkbox" name="rgroup" value="true"></td></tr>
                <tr><td><label>Adresse DALI : </label></td><td><input name="raddress" value="0"></td></tr>
                <tr><td><label>Dur&eacute;e de transition : </label></td><td><input name="rfade_time" value="1"></td></tr>
                <tr><td><b><em>Couleur verte:</em></b></tr></td>
                <tr><td><label>Module DALI : </label></td><td><input name="gline" value="1"></td></tr>
                <tr><td><label>Adresse de groupe? : </label></td><td><input type="checkbox" name="ggroup" value="true"></td></tr>
                <tr><td><label>Adresse DALI : </label></td><td><input name="gaddress" value="0"></td></tr>
                <tr><td><label>Dur&eacute;e de transition : </label></td><td><input name="gfade_time" value="1"></td></tr>
                <tr><td><b><em>Couleur bleu:</em></b></tr></td>
                <tr><td><label>Module DALI : </label></td><td><input name="bline" value="1"></td></tr>
                <tr><td><label>Adresse de groupe? : </label></td><td><input type="checkbox" name="bgroup" value="true"></td></tr>
                <tr><td><label>Adresse DALI : </label></td><td><input name="baddress" value="0"></td></tr>
                <tr><td><label>Dur&eacute;e de transition : </label></td><td><input name="bfade_time" value="1"></td></tr>
<?php } ?>
        </tbody></table>
        </td></tr>
      <tr>
        <td style="float: right;">
<button dojoType="Button" onclick="CreateState(3,'<?php echo $radio; ?>',<?php echo "'$room_type',$room_id,'".urldecode($room_name)."'"; ?>); return true;">
<div class="inside_button">Cr&eacute;er</div></button>
        </td>
      </tr>
    </tbody>
  </table>
</form>
</div>
<?php
        }
?>