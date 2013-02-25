<?php
        //Check user identity
        require "auth.php";
?>
<h1 class="list_header">Redémarage de l'interface :</h1>
<table style="text-align: left; border: 2px solid grey;" width="100%" border="0" cellpadding="2" cellspacing="2">
<tbody>
    <tr>
      <td><img style="width: 128px; height: 128px;" alt="save" src="img/reloadtouchscreen_big.png"></td>
      <td style="vertical-align: top;">Effectuer un redémarrage de l'interface tactile. Cela peut
      être utile si l'interface reste bloqué.<br/><br/><br/><br/>
<p style="float: right;">
<button dojoType="Button" onclick="Reboot('calaos_gui'); return true;">
<div class="inside_button">Redémarrer</div></button>
</p>
</td>
    </tr>
  </tbody>
</table>
<h1 class="list_header">Redémarage du serveur :</h1>
<table style="text-align: left; border: 2px solid grey;" width="100%" border="0" cellpadding="2" cellspacing="2">
<tbody>
    <tr>
      <td><img style="width: 128px; height: 128px;" alt="save" src="img/reloadserver_big.png"></td>
      <td style="vertical-align: top;">Effectuer un redémarrage du service de gestion domotique. <span
 style="font-weight: bold;"><br/><br/>Attention!</span><br/>Pensez à sauvegarder la configuration avant de redémarrer.
 <br/><br/><br/><br/>
<p style="float: right;">
<button dojoType="Button" onclick="Reboot('calaosd'); return true;">
<div class="inside_button">Redémarrer</div></button>
</p>
</td>
    </tr>
  </tbody>
</table>
<h1 class="list_header">Redémarage complet :</h1>
<table style="text-align: left; border: 2px solid grey;" width="100%" border="0" cellpadding="2" cellspacing="2">
<tbody>
    <tr>
      <td><img style="width: 128px; height: 128px;" alt="save" src="img/reload_big.png"></td>
      <td style="vertical-align: top;">Effectuer un redémarrage complet du système.
      <span
 style="font-weight: bold;"><br/><br/>Attention!</span><br/>Pensez à sauvegarder la configuration avant de redémarrer.
 <br/><br/><br/><br/>
<p style="float: right;">
<button dojoType="Button" onclick="Reboot('system'); return true;">
<div class="inside_button">Redémarrer</div></button>
</p>
</td>
    </tr>
  </tbody>
</table>
