<?php
        //Check user identity
        require "auth.php";

        //Get config
        require_once "Utils.php";
?>
<h1 class="list_header">Service SSH :</h1>
<table width="100%" border="0" cellpadding="2" cellspacing="2">
<tbody>
<tr>
<td style="text-align: left; border: 1px solid grey;" width="50%">
    <table width="100%" border="0" cellpadding="2" cellspacing="2">
    <tbody>
        <tr>
          <td align="right">Etat du serveur SSH: </td>
          <td><input class="inputbox" id="calaos_ssh" size="14" type="text" readonly
          value="<?php if (isSSHRunning()) echo 'Démarré'; else echo 'Arrêté'; ?>" /></td>
        </tr>
        <tr>
          <td align="right">Recréer les clés RSA: </td>
          <td><button dojoType="Button" onclick="SSHDeleteKey(); return true;">
                <div class="inside_button">Recréer</div></button>
          </td>
        </tr>
        <tr>
          <td align="right">Activer/Désactiver le service SSH: </td>
          <td><input class="inputbox" id="ssh_enable" type="checkbox" <?php if (getConfigOption("ssh_enable") == "true") echo "checked" ?> /></td>
        </tr>
    </tbody>
    </table>
</td>
<td style="text-align: left; border: 1px solid grey; text-align: justify;">
    <img style="vertical-align: middle;" alt="info" src="img/info.png"/>
Vous pouvez sur cette page activer/désactiver le service SSH. Ce service sert à se connecter en console (voir <a href="http://support.calaos.fr/centrale_ssh">Accès SSH</a>.<br />
<br />
<br />
<br />
</td>
</tr>
<tr>
<td></td>
<td align="right">
<button dojoType="Button" onclick="UpdateSSHConfig(); return true;">
<div class="inside_button">Valider</div></button>
</td>
</tr></tbody>
</table>
