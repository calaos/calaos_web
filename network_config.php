<?php
        //Check user identity
        require "auth.php";

        //Get config
        require_once "Utils.php";
?>
<h1 class="list_header">Param&egrave;tres r&eacute;seau :</h1>
<table width="100%" border="0" cellpadding="2" cellspacing="2">
<tbody><tr>
<td><em>(eth0)</em> R&eacute;seau local :</td>
<td><em>(eth1)</em> R&eacute;seau automate :</td>
</tr><tr>
<td style="text-align: left; border: 1px solid grey;">
    <table width="100%" border="0" cellpadding="2" cellspacing="2">
    <tbody>
        <tr>
          <td align="right">Utilisation du DHCP : </td>
          <td><input class="inputbox" id="eth0_dhcp" type="checkbox" <?php if (getConfigOption("eth0_dhcp") == "true") echo "checked" ?> /></td>
        </tr>
        <tr>
          <td align="right">Adresse IP: </td>
          <td><input class="inputbox" id="eth0_address" size="14" maxlength="15" type="text"
          <?php echo 'value="'.getConfigOption("eth0_address").'"' ?> /></td>
        </tr>
        <tr>
          <td align="right">Masque r&eacute;seau: </td>
          <td><input class="inputbox" id="eth0_netmask" size="14" maxlength="15" type="text"
          <?php echo 'value="'.getConfigOption("eth0_netmask").'"' ?> /></td>
        </tr>
        <tr>
          <td align="right">Adresse de diffusion: </td>
          <td><input class="inputbox" id="eth0_broadcast" size="14" maxlength="15" type="text"
          <?php echo 'value="'.getConfigOption("eth0_broadcast").'"' ?> /></td>
        </tr>
        <tr>
          <td align="right">Passerelle: </td>
          <td><input class="inputbox" id="eth0_gateway" size="14" maxlength="15" type="text"
          <?php echo 'value="'.getConfigOption("eth0_gateway").'"' ?> /></td>
        </tr>
    </tbody>
    </table>
</td>
<td style="text-align: left; border: 1px solid grey;">
    <table width="100%" border="0" cellpadding="2" cellspacing="2">
    <tbody>
        <tr>
          <td align="right">Adresse IP: </td>
          <td><input class="inputbox" id="eth1_address" size="14" maxlength="15" type="text"
          <?php echo 'value="'.getConfigOption("eth1_address").'"' ?> /></td>
        </tr>
        <tr>
          <td align="right">Masque r&eacute;seau: </td>
          <td><input class="inputbox" id="eth1_netmask" size="14" maxlength="15" type="text"
          <?php echo 'value="'.getConfigOption("eth1_netmask").'"' ?> /></td>
        </tr>
        <tr>
          <td align="right">Adresse de diffusion: </td>
          <td><input class="inputbox" id="eth1_broadcast" size="14" maxlength="15" type="text"
          <?php echo 'value="'.getConfigOption("eth1_broadcast").'"' ?> /></td>
        </tr>
        <tr>
          <td align="right">Passerelle: </td>
          <td><input class="inputbox" id="eth1_gateway" size="14" maxlength="15" type="text"
          <?php echo 'value="'.getConfigOption("eth1_gateway").'"' ?> /></td>
        </tr>
    </tbody>
    </table>
</td>
</tr><tr>
<td style="text-align: left; border: 1px solid grey;">
Syst&egrave;me de noms de domaines :
    <table width="100%" border="0" cellpadding="2" cellspacing="2">
    <tbody>
        <tr>
          <td align="right">Serveur DNS: </td>
          <td><input class="inputbox" id="dns_address" size="14" maxlength="15" type="text"
          <?php echo 'value="'.getConfigOption("dns_address").'"' ?> /></td>
        </tr>
    </tbody>
    </table>
</td>
<td></td>
</tr>
<tr>
<td></td>
<td align="right">
<button dojoType="Button" onclick="UpdateNetworkConfig(); return true;">
<div class="inside_button">Valider</div></button>
</td>
</tr></tbody>
</table>
