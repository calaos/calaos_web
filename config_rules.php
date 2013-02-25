<?php
        //Check user identity
        require "auth.php";

        require_once "Calaos.php";

        $calaos = Calaos::Instance();

        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

        //get the number of rules
        $res = explode(" ", $calaos->SendRequest("rules ?"));
?>
<h1 class="title">Edition des r&egrave;gles</h1>
<table width="90%" style="border: 1px;margin-top:1em;" border="0" cellpadding="0" cellspacing="1">
<tr class="list_header">
<td>Type :</td>
<td>Nombre :</td>
<td style="background-color:white;"></td>
</tr>
<?php
        for ($i = 1;$i < count($res);$i++)
        {
                if (urldecode($res[$i]) == "?") continue;
                list($type, $nb) = explode(":", urldecode($res[$i]), 2);
?>
<tr>
<td><?php echo $type; ?></td>
<td><?php echo $nb; ?></td>
<td>
<button dojoType="Button" onclick="ListRule(<?php echo "'".addslashes($type)."',$nb"; ?>); return true;">
<div class="inside_button">Editer</div></button>
</td>
</tr>
<?php
        }
?>
</table>
<button dojoType="Button" onclick="NewRule(''); return true;">
<div class="inside_button">Nouvelle r&egrave;gle</div></button>