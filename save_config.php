<?php
        //Check user identity
        require "auth.php";
?>
<h1 class="list_header">Sauvegarde :</h1>
<table style="text-align: left; border: 2px solid grey;" border="0" cellpadding="2" cellspacing="2">
<tbody>
    <tr>
      <td><img style="width: 128px; height: 128px;" alt="save" src="img/save_big.png"></td>
      <td style="vertical-align: top;">Effectuer une sauvegarde de la totalit&eacute; de
la configuration dans la m&eacute;moire flash.<br/>Cette sauvegarde
servira de base de travail lors de red&eacute;marrage du
syt&egrave;me.
<p style="float: right;">
<button dojoType="Button" onclick="Save('standard'); return true;">
<div class="inside_button">Sauvegarde</div></button>
</p>
</td>
    </tr>
  </tbody>
</table>
<h1 class="list_header">Sauvegarde par d&eacute;faut :</h1>
<table style="text-align: left; border: 2px solid grey;" border="0" cellpadding="2" cellspacing="2">
<tbody>
    <tr>
      <td><img style="width: 128px; height: 128px;" alt="save" src="img/save_big.png"></td>
      <td style="vertical-align: top;">Effectuer une sauvegarde de&nbsp;la
totalit&eacute; de
la configuration comme sauvegarde par d&eacute;faut. Cette
sauvegarde est utilis&eacute; en cas de d&eacute;faillance et
permet d'avoir un syst&egrave;me op&eacute;rationel en cas de
probl&egrave;me avec la configuration de travail. <span
 style="font-weight: bold;"><br/><br/>Attention!</span> A
utiliser avec pr&eacute;caution.
<p style="float: right;">
<button dojoType="Button" onclick="Save('default'); return true;">
<div class="inside_button">Sauvegarde</div></button>
</p>
</td>
    </tr>
  </tbody>
</table>
