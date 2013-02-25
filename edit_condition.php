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
        $cid = @$_GET['cid'];
        if (!isset($cid)) die ("Error: cid not set...");

        //get the number of rules
        $input_list = explode(" ", $calaos->SendRequest("input list"));

        $condvar = "";
        $cres = explode(" ", $calaos->SendRequest("rules $type get $id condition"));
        for ($i = 3;$i < count($cres);$i++)
        {
                list($ival, $ikey, $ivalue) = explode(":", urldecode($cres[$i]), 3);
                if ($ival == $cid)
                {
                        if ($ikey == "id") $condid = $ivalue;
                        if ($ikey == "val") $condval = $ivalue;
                        if ($ikey == "oper") $condoper = $ivalue;
                        if ($ikey == "var_val") $condvar = $ivalue;
                }
        }
?>
<div style="text-align: center;width: 600px; padding: 5px;">
<h1 class="title">Edition de la condition</h1>
<table width="100%" style="border: 0px;margin-top:1em;" border="0" cellpadding="0" cellspacing="1">
<tr>
<td style="vertical-align: bottom;">
<select id="edit_condition_id" style="width: 200px;">
<?php
        populate("input", $condid);
?>
</select>
</td>
<td style="vertical-align: bottom;">
<select id="edit_condition_oper" style="width: 50px;">
        <option value="=="<?php if ($condoper == "==") echo " selected" ?>>==</option>
        <option value="!="<?php if ($condoper == "!=") echo " selected" ?>>&lt;&gt;</option>
        <option value="INF"<?php if ($condoper == "INF") echo " selected" ?>>&lt;</option>
        <option value="SUP"<?php if ($condoper == "SUP") echo " selected" ?>>&gt;</option>
        <option value="INF="<?php if ($condoper == "INF=") echo " selected" ?>>&lt;=</option>
        <option value="SUP="<?php if ($condoper == "SUP=") echo " selected" ?>>&gt;=</option>
</select>
</td>
<td style="vertical-align: bottom;">
<table style="border: 0px;margin-top:1em;" border="0" cellpadding="0" cellspacing="1">
<tr><td>
<input type="checkbox" name="check_var_val" widgetId="check_var_val" value="cvar_val" dojoType="Checkbox" <?php if ($condvar != "") echo "checked"; ?>>
        <label for="check_var_val"></label></td><td>
<select id="edit_condition_val" style="width: 200px;">
<?php
        populate("input", $condvar);
?>
</select></td></tr>
<tr><td>
<label>Valeur: </label></td><td><input name="name" id="condition_value" value="<?php echo $condval; ?>">
<a href="javascript:HelpCondition('edit_condition_id');">
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
<button dojoType="Button" onclick="EditConditionValid(<?php echo "'".addslashes($type)."',$id,$cid,$nb"; ?>); return true;">
<div class="inside_button">Valider</div></button>
</td></tr></table>
</div>
</div>