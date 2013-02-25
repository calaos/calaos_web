<?php
        //Check user identity
        require "auth.php";

        //Get config
        require_once "Utils.php";
?>
<h1 class="list_header">Mise &agrave; jour du logiciel :</h1>
<table width="100%" border="0" cellpadding="2" cellspacing="2" style="text-align: center; border: 1px solid grey;">
<tbody><tr>
<td>
Url de mise &agrave; jour: 
</td>
<td>
<input class="inputbox" id="update_url" size="50" type="text"
        <?php echo 'value="'.getConfigOption("update_url").'"' ?> />
</td>
<td>
<button dojoType="Button" onclick="doUpdateFWurl(); return true;">
<div class="inside_button">Modifier</div></button>
</td>
</tr></tbody>
</table>
<table width="100%" border="0" cellpadding="2" cellspacing="2">
<tbody><tr>
<td>Firmware :</td>
<td></td>
</tr><tr>
<td style="text-align: center; border: 1px solid grey;" width="50%">
<div id="form_upload">
<form id="uploadform" action="uploadfw.php" method="POST" enctype="multipart/form-data">
S&eacute;lectionnez un firmware: 
<input class="inputbox" name="firmware" type="file" />
</form>
</div>
<div id="upload_load" style="display:none;">
<img style="vertical-align: middle;" alt="loading" src="img/loading_roller.gif" />
Chargement du fichier...
</div>
    <br/>
    La version actuelle du firmware est <em><?php echo getConfigOption("fw_version"); ?></em>
</td>
<td style="text-align: left; border: 1px solid grey; text-align: justify;">
    <img style="vertical-align: middle;" alt="info" src="img/info.png"/>
Vous pouvez sur cette page mettre &agrave; jour manuellement le logiciel du syst&egrave;me Calaos.
Ces mises &agrave; jour permettent de profiter des ajouts de nouvelles fonctionnalit&eacute;s, ainsi que les
diff&eacute;rents correctifs disponibles.<br/><br/>
Vous pouvez t&eacute;l&eacute;charger les nouveaux <em><a href="http://fr.wikipedia.org/wiki/Firmware">firmware</a></em>
sur le site web Calaos suivant: <a href="http://update.calaos.fr">http://update.calaos.fr</a>.
</td>
</tr>
<tr>
<td></td>
<td align="right">
<button dojoType="Button" onclick="doUpdateFW(); return true;">
<div class="inside_button">Mettre &agrave; jour</div></button>
</td>
</tr></tbody>
</table>

