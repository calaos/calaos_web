<?php
        //Check user identity
        require "auth.php";

        require_once "Calaos.php";

        $calaos = Calaos::Instance();

        include_once "populate_select.php";

        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

        $type = stripslashes(@$_GET['type']);
        if (!isset($type)) die ("Error: type not set...");
        $id = @$_GET['id'];
        if (!isset($id)) die ("Error: id not set...");
        $nb = @$_GET['nb'];
        if (!isset($nb)) die ("Error: nb not set...");
        $aid = @$_GET['aid'];
        if (!isset($aid)) die ("Error: aid not set...");

        //get the number of rules
        $output_list = explode(" ", $calaos->SendRequest("output list"));

        $cres = explode(" ", $calaos->SendRequest("rules $type get $id action"));
        for ($i = 3;$i < count($cres);$i++)
        {
                list($ival, $ikey, $ivalue) = explode(":", urldecode($cres[$i]), 3);
                if ($ival == $aid)
                {
                        if ($ikey == "id") $condid = $ivalue;
                        if ($ikey == "val") $condval = $ivalue;
                        if ($ikey == "var_val") $condvar = $ivalue;
                }
        }
?>
<div style="text-align: center;width: 600px; padding: 5px;">
<h1 class="title">Edition de l'action</h1>
<table width="100%" style="border: 0px;margin-top:1em;" border="0" cellpadding="0" cellspacing="1">
<tr>
<td style="vertical-align: bottom;">
<select id="edit_action_id" style="width: 200px;">
<?php
        populate("output", $condid);
?>
</select>
</td>
<td style="vertical-align: bottom;">
<table style="border: 0px;margin-top:1em;" border="0" cellpadding="0" cellspacing="1">
<tr><td>
<input type="checkbox" name="check_var_val" widgetId="acheck_var_val" value="avar_val" dojoType="Checkbox" <?php if ($condvar != "") echo "checked"; ?>>
        <label for="acheck_var_val"></label></td><td>
<select id="edit_action_val" style="width: 200px;">
<?php
        populate("output", $condvar);
?>
</select></td></tr>
<tr><td><label>Valeur: </label></td><td><input name="name" id="action_value" value="<?php echo $condval; ?>">
<a href="javascript:HelpAction('edit_action_id');">
<img src="img/help_small.png" alt="help" />
</a>
</td></tr>
</table>
</td>
</tr>
</table>
<div style="float:right">
<table style="border: 0px;margin-top:1em;" border="0" cellpadding="0" cellspacing="1">
<tr>
<td>
<button dojoType="Button" onclick="EditCancel(); return true;">
<div class="inside_button">Annuler</div></button>
</td>
<td>
<button dojoType="Button" onclick="EditActionValid(<?php echo "'".addslashes($type)."',$id,$aid,$nb"; ?>); return true;">
<div class="inside_button">Valider</div></button>
</td></tr></table>
</div>
</div>