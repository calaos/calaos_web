<?php
        //Check user identity
        require "auth.php";

        require_once "Calaos.php";
        $calaos = Calaos::Instance();

        function get_io($type, $id, $param)
        {
                global $calaos;
                $res = explode(" ", $calaos->SendRequest("$type $id get"));
                for ($i = 1;$i < count($res);$i++)
                {
                        list($key, $value) = explode(":", urldecode($res[$i]), 2);
                        if ($key == $param) return $value;
                }
        }

        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

        $type = stripslashes(@$_GET['type']);
        if (!isset($type))
                die ("Error: type not set...");
        $id = stripslashes(@$_GET['id']);
        if (!isset($id))
                die ("Error: id not set...");
        $_nb = @$_GET['nb'];
        if (!isset($_nb))
                die ("Error: nb not set...");

        $cres = explode(" ", $calaos->SendRequest("rules ".rawurlencode($type)." get $id condition"));
        $cpt = 0;
?>
<h1 class="title">Edition de la r&egrave;gle <em><?php echo "$type:$name"; ?></em></h1>
<em><a href="javascript:ShowRules();">Configuration</a> > <a href="javascript:ListRule(<?php echo "'".addslashes($type)."',$_nb"; ?>);">R&egrave;gles "<?php echo $type; ?>"</a></em>
<h1 class="list_header">Conditions :</h1>
<table width="90%" style="border: 1px;margin-top:1em;" border="0" cellpadding="0" cellspacing="1">
<?php
        for ($i = 3;$i < count($cres);$i++)
        {
                list($cid, $key, $value) = explode(":", urldecode($cres[$i]), 3);
                if ($cid != $cpt)
                {
                        $cpt = $cid;
                        $new_cond = true;
                }
                else
                {
                        if ($key == "id") { $iid = $value; $val = ""; $var_val = ""; }
                        if ($key == "oper") $oper = $value;
                        if ($key == "val") $val = $value;
                        if ($key == "var_val") $var_val = $value;
                }

                if ($new_cond || $i == count($cres) - 1)
                {
                        $new_cond = false;

                        $in_name = get_io("input", $iid, "name");
                        if ($var_val != "") $var_val_name = get_io("input", $var_val, "name");

                        if ($oper == "INF") $oper = "<";
                        if ($oper == "INF=") $oper = "<=";
                        if ($oper == "SUP") $oper = ">";
                        if ($oper == "SUP=") $oper = ">=";
                        if ($oper == "!=") $oper = "<>";

                        if ($i == count($cres) - 1)
                                $condition_id = $cpt;
                        else
                                $condition_id = $cpt - 1;
?>
<tr>
<?php
                        if (count($cres) > 7)
                        {
?>
<td style="text-align: center; width: 30px;">
<a href="#" onclick="delete_condition(<?php echo "'".addslashes($type)."',$id,$condition_id,$_nb"; ?>); return true;">
<img id="del" alt="del" src="img/trash.png" /></a>
</td>
<?php
                        }
?>
<td>
<?php
        echo "<strong>Si</strong> <em>$in_name</em> <strong>$oper</strong> ";
        if ($var_val != "")
                echo "<em>$var_val_name</em>";
        else
                echo "<em>$val</em>";
?></td>
<td style="float: right;">
<button dojoType="Button" onclick="EditCondition(<?php echo "'".addslashes($type)."',$id,$condition_id,$_nb"; ?>); return true;">
<div class="inside_button">Editer</div></button>
</td>
</tr>
<?php
                        if ($key == "id") { $iid = $value; $val = ""; $var_val = ""; }
                        if ($key == "oper") $oper = $value;
                        if ($key == "val") $val = $value;
                        if ($key == "var_val") $var_val = $value;
                }
        }
?>
</table>
<button dojoType="Button" onclick="NewCondition(<?php echo "'".addslashes($type)."',$id,$_nb"; ?>); return true;">
<div class="inside_button">Nouvelle condition</div></button>
<?php
        $ares = explode(" ", $calaos->SendRequest("rules ".rawurlencode($type)." get $id action"));
        $cpt = 0;
?>
<h1 class="list_header">Actions :</h1>
<table width="90%" style="border: 1px;margin-top:1em;" border="0" cellpadding="0" cellspacing="1">
<?php
        for ($i = 3;$i < count($ares);$i++)
        {
                list($aid, $key, $value) = explode(":", urldecode($ares[$i]), 3);
                if ($aid != $cpt)
                {
                        $cpt = $aid;
                        $new_act = true;
                }
                else
                {
                        if ($key == "id") { $oid = $value; $val = ""; $var_val = ""; }
                        if ($key == "val") $val = $value;
                        if ($key == "var_val") $var_val = $value;
                }

                if ($new_act || $i == count($ares) - 1)
                {
                        $new_act = false;

                        $out_name = get_io("output", $oid, "name");
                        if ($var_val != "") $var_val_name = get_io("output", $var_val, "name");

                        if ($i == count($ares) - 1)
                                $action_id = $cpt;
                        else
                                $action_id = $cpt - 1;
?>
<tr>
<?php
                        if (count($ares) > 5)
                        {
?>
<td style="text-align: center; width: 30px;">
<a href="#" onclick="delete_action(<?php echo "'".addslashes($type)."',$id,$action_id,$_nb"; ?>); return true;">
<img id="del" alt="del" src="img/trash.png" /></a>
</td>
<?php
                        }
?>
<td>
<?php
        echo "<strong>Faire</strong> <em>$out_name</em> <img src=\"img/arrowright.png\" alt=\"arrow\"/> ";
        if ($var_val != "")
                echo "<em>$var_val_name</em>";
        else
                echo "<em>$val</em>";
?>
</td>
<td style="float: right;">
<button dojoType="Button" onclick="EditAction(<?php echo "'".addslashes($type)."',$id,$action_id,$_nb"; ?>); return true;">
<div class="inside_button">Editer</div></button>
</td>
</tr>
<?php
                        if ($key == "id") { $oid = $value; $val = ""; $var_val = ""; }
                        if ($key == "val") $val = $value;
                        if ($key == "var_val") $var_val = $value;
                }
        }
?>
</table>
<button dojoType="Button" onclick="NewAction(<?php echo "'".addslashes($type)."',$id,$_nb"; ?>); return true;">
<div class="inside_button">Nouvelle action</div></button>