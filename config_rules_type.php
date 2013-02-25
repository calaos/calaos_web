<?php
        //Check user identity
        require "auth.php";

        require_once "Calaos.php";

        $calaos = Calaos::Instance();

        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

        $type = stripslashes(@$_GET['type']);
        if (!isset($type))
                die ("Error: type not set...");
        $nb = @$_GET['nb'];
        if (!isset($nb))
                die ("Error: nb not set...");

?>
<h1 class="title">Edition des r&egrave;gles de type <em><?php echo $type; ?></em></h1>
<em><a href="javascript:ShowRules();">Configuration</a></em>
<table width="90%" style="border: 1px;margin-top:1em;" border="0" cellpadding="0" cellspacing="1">
<tr class="list_header">
<td style="background-color:white;"></td>
<td>Nom :</td>
<td style="background-color:white;"></td>
</tr>
<?php
        for ($i = 0;$i < $nb;$i++)
        {
                $res = explode(" ", $calaos->SendRequest("rules ".rawurlencode($type)." get $i condition"));
                list($key, $name) = explode(":", urldecode($res[2]), 2);
?>
<tr>
        <td style="text-align: center; width: 30px;">
        <a href="#" onclick="delete_rule(<?php echo "'".addslashes($type)."',$i,$nb"; ?>); return true;">
        <img id="del" alt="del" src="img/trash.png" /></a>
        </td>
<td><?php echo $name; ?></td>
        <td>
        <button dojoType="Button" onclick="EditRule(<?php echo "'".addslashes($type)."',$i,$nb"; ?>); return true;">
        <div class="inside_button">Editer</div></button>
        </td>
</tr>
<?php
        }
?>
</table>
<button dojoType="Button" onclick="NewRule(<?php echo "'".addslashes($type)."'"; ?>); return true;">
<div class="inside_button">Nouvelle r&egrave;gle</div></button>