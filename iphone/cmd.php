<?php
        //Check user identity
        require "../auth.php";
        require_once "../Calaos.php";
        require_once "../Utils.php";

        $calaos = Calaos::Instance();

        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

        $id = @$_GET['id'];
        $action = @$_GET['cmd'];

        if (!isset($action))
                exit(0);

        if($action == "play")
        {
                $calaos->SendRequest("audio ".$id." play");
        }
        if($action == "stop")
        {
                $calaos->SendRequest("audio ".$id." stop");
        }
        if($action == "next")
        {
                $calaos->SendRequest("audio ".$id." next");
        }
        if($action == "previous")
        {
                $calaos->SendRequest("audio ".$id." previous");
        }
        if($action == "songinfo")
        {
                $res = explode(" ", $calaos->SendRequest("audio ".$id." songinfo?"));
                for ($j = 0;$j < count($res);$j++)
                {
                        list($opt, $val) = explode(":", urldecode($res[$j]), 2);
                        if ($opt == "title") $title = urldecode($val);
                        if ($opt == "artist") $artist = urldecode($val);
                        if ($opt == "album") $album = urldecode($val);
                }

                echo $artist."<br /><i>".$title."</i><br />";
                echo "<i>".$album."</i>";
        }

?>