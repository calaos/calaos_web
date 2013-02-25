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

        $input_list = explode(" ", $calaos->SendRequest("input list"));
?>
<div style="text-align: center;width: 600px; padding: 5px;">
<h1 class="title">Nouvelle condition</h1>
<table width="100%" style="border: 0px;margin-top:1em;" border="0" cellpadding="0" cellspacing="1">
<tr>
<td style="vertical-align: bottom;">
<select id="edit_condition_id" style="width: 200px;">
<?php
        populate("input");
?>
</select>
</td>
<td style="vertical-align: bottom;">
<select id="edit_condition_oper" style="width: 50px;">
        <option value="==">==</option>
        <option value="!=">&lt;&gt;</option>
        <option value="INF">&lt;</option>
        <option value="SUP">&gt;</option>
        <option value="INF=">&lt;=</option>
        <option value="SUP=">&gt;=</option>
</select>
</td>
<td style="vertical-align: bottom;">
<table style="border: 0px;margin-top:1em;" border="0" cellpadding="0" cellspacing="1">
<tr><td>
<input type="checkbox" name="check_var_val" widgetId="check_var_val" value="cvar_val" dojoType="Checkbox">
        <label for="check_var_val"></label></td><td>
<select id="edit_condition_val" style="width: 200px;">
<?php
        populate("input");
?>
</select></td></tr>
<tr><td><label>Valeur: </label></td><td><input name="name" id="condition_value" value=""></td></tr>
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
<button dojoType="Button" onclick="NewConditionValid(<?php echo "'".addslashes($type)."',$id,$nb"; ?>); return true;">
<div class="inside_button">Valider</div></button>
</td></tr></table>
</div>
</div>