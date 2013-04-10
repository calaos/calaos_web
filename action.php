<?php
        define('ENNA_WWW', 1);
        include_once('common.php');
        define('PAGE_NAV', 0);

        require_once('ConfigParser.php');
        require_once('DetectServer.php');

        function die_error($error_forbidden = true)
        {
                if ($error_forbidden)
                {
                        header($_SERVER["SERVER_PROTOCOL"]." 403 Forbidden");
                        exit();
                }
                else
                {
                        $value = array("error" => 1);
                        die (json_encode($value));
                }
        }

        $data = file_get_contents("php://input");

        header("Content-type: application/json");

        $jdata = json_decode($data, true);

        if ($jdata == NULL)
        {
                //If json_decode failed, we can try to get the json from traditionnal Form POST
                if (isset($_POST["json"]))
                {
                        $jdata = json_decode(stripcslashes($_POST["json"]), true);
                        if ($jdata == NULL)
                                die_error();
                }
                else
                {
                        die_error();
                }
        }

        if ($jdata['action'] == 'music_source')
        {
                if ($jdata['cmd'] == 'list')
                {
                        $d = new DetectServer();
                        $d->discover();
                        $value = array('action' => 'music_source',
                                       'cmd' => 'list',
                                       'result' => $d->getServerList());
                        die (json_encode($value));
                }
        }

        //Error unknown command/action
        die_error();
?>