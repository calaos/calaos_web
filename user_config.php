<?php
        //Check user identity
        require "auth.php";

        //Get config
        require_once "Utils.php";
?>
<h1 class="list_header">Param&egrave;tres utilisateur :</h1>
<table width="100%" border="0" cellpadding="2" cellspacing="2">
<tbody><tr>
<td>Identifiant / mot de passe :</td>
<td></td>
</tr><tr>
<td style="text-align: left; border: 1px solid grey;" width="50%">
    <table width="100%" border="0" cellpadding="2" cellspacing="2">
    <tbody>
        <tr>
          <td align="right">Nom d'utilisateur: </td>
          <td><input class="inputbox" id="calaos_user" size="14" type="text"
          <?php echo 'value="'.getConfigOption("calaos_user").'"' ?> /></td>
        </tr>
        <tr>
          <td align="right">Mot de passe: </td>
          <td><input class="inputbox" id="calaos_password" size="14" type="password"
          <?php echo 'value="'.getConfigOption("calaos_password").'"' ?> /></td>
        </tr>
        <tr>
          <td align="right">Confirmez le mot de passe: </td>
          <td><input class="inputbox" id="calaos_password_bis" size="14" type="password"
          <?php echo 'value="'.getConfigOption("calaos_password").'"' ?> /></td>
        </tr>
    </tbody>
    </table>
</td>
<td style="text-align: left; border: 1px solid grey; text-align: justify;">
    <img style="vertical-align: middle;" alt="info" src="img/info.png"/>
Vous pouvez changer sur cette page les identifiants de connexion au syst&egrave;me Calaos.
Ces identifiants vont vous permettre de vous connecter sur la page Web, ainsi que d'<em>authentifier</em>
les diff&eacute;rents &eacute;crans tactiles.<br/><br/>
<strong>Attention !</strong> Si vous disposez de plusieurs &eacute;crans tactiles, vous devrez &eacute;galement
modifier les identifiants sur ceux-ci afin qu'ils correspondent.
</td>
</tr>
<tr>
<td></td>
<td align="right">
<button dojoType="Button" onclick="UpdateUserConfig(); return true;">
<div class="inside_button">Valider</div></button>
</td>
</tr></tbody>
</table>
