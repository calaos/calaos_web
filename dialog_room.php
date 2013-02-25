<?php
        //Check user identity
        require "auth.php";

        require_once "Calaos.php";

        $calaos = Calaos::Instance();

        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

        $room_type = @$_GET['room_type'];
        if (!isset($room_type))
                die ("Error: room_type not set...");

        $res = explode(" ", $calaos->SendRequest("home get ".$room_type));
        list($str, $countr) = explode(":", urldecode($res[1]), 2);
        if ($countr <= 0) continue;
?>
<div style="text-align: left;width: 300px;">
<h1 class="list_header">Veuillez choisir une pièce :</h1>
<div class="list_scenario">
<?php
        for ($j = 2;$j < count($res);$j++)
        {
                list($id, $opt, $value) = explode(":", urldecode($res[$j]), 3);
                if ($opt == "name")
                {
?>
<a href="#" onclick="ShowRoomMultiple('<?php echo $room_type; ?>',<?php echo $id; ?>); return true;" class="sub_scenario">
<?php echo $value; ?>
</a>
<?php
                }
        }
?>
</div>
</div>