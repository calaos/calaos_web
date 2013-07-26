<?php
        require_once("Utils.php");
        require_once("home_api.php");

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

        if (isset($_GET["callback_jsonp"]))
        {
                $jsonp = true;

                //Get data from GET
                $jdata["callback_jsonp"] = $_GET["callback_jsonp"];
                $jdata["cn_user"] = $_GET["cn_user"];
                $jdata["cn_pass"] = $_GET["cn_pass"];
                $jdata["action"] = $_GET["action"];

                //Prevent an XSS attack
                if (preg_match('/\W/', $_GET['callback']))
                        die_error();
        }
        else
        {
                $jsonp = false;

                //Get data from php/input
                $data = file_get_contents("php://input");
                $jdata = json_decode($data, true);
        }

        //Try to enable CORS requests
        if (isset($_SERVER['HTTP_ORIGIN']))
        {
                header("Access-Control-Allow-Origin: ".$_SERVER['HTTP_ORIGIN']);
                header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        }

        //handle the OPTIONS request for CORS
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
                if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

                if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

                exit();
        }

        header("Content-type: application/json");

        if ($jdata == NULL)
        {
                //If json_decode failed, we can try to get the json from traditionnal Form POST
                if (isset($_POST["json"]))
                {
                        $jdata = json_decode(stripcslashes($_POST["json"]), true);

                        if ($jdata == NULL)
                                die_error();

                        if (isset($_FILES["file"]))
                        {
                                //We have a file POST here.
                                $uploadfile = "/tmp/calaos_web_file.tmp";
                                if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile))
                                {
                                        die_error();
                                }
                        }
                }
                else
                {
                        die_error();
                }
        }

        if (getConfigOption("cn_user") != "" &&
            getConfigOption("cn_pass") != "")
        {
                $user = getConfigOption("cn_user");
                $pass = getConfigOption("cn_pass");
        }
        else
        {
                $user = getConfigOption("calaos_user");
                $pass = getConfigOption("calaos_password");
        }

        if ($user != $jdata["cn_user"])
                die_error();

        if ($pass != $jdata["cn_pass"])
                die_error();

        if ($jdata["action"] == "login")
        {
                $value = array("cn_user" => $jdata["cn_user"],
                               "login" => true);
                if ($jsonp)
                        die ($jdata["callback_jsonp"] . "(" . json_encode($value) . ");");
                else
                        die (json_encode($value));
        }

        if ($jdata["action"] == "get_files")
        {
                $value = array("cn_user" => $jdata["cn_user"],
                               "io.xml" => base64_encode(file_get_contents("/mnt/ext3/calaos/io.xml")),
                               "rules.xml" => base64_encode(file_get_contents("/mnt/ext3/calaos/rules.xml")),
                               "local_config.xml" => base64_encode(file_get_contents("/mnt/ext3/calaos/local_config.xml")));
                die (json_encode($value));
        }

        if ($jdata["action"] == "set_files" &&
            $jdata["io.xml"] != "" &&
            $jdata["rules.xml"] != "")
        {
                $xml_io = base64_decode($jdata["io.xml"]);
                if (simplexml_load_string($xml_io) === false)
                        die_error();

                $xml_rules = base64_decode($jdata["rules.xml"]);
                if (simplexml_load_string($xml_rules) === false)
                        die_error();

                $fp = @fopen("/mnt/ext3/calaos/io.xml", "w");
                @fwrite($fp, $xml_io);
                @fclose($fp);

                $fp = @fopen("/mnt/ext3/calaos/rules.xml", "w");
                @fwrite($fp, $xml_rules);
                @fclose($fp);

                if ($jdata["reload"] === true)
                {
                        shell_exec("killall -9 calaosd");
                }

                $value = array("cn_user" => $jdata["cn_user"],
                               "success" => true);
                die (json_encode($value));
        }

        if ($jdata["action"] == "get_home")
        {
                die (json_encode(getHomeArray()));
        }

        if ($jdata["action"] == "get_state")
        {
                die (json_encode(getStatusArray($jdata["inputs"], $jdata["outputs"], $jdata["audio_players"])));
        }

        if ($jdata["action"] == "set_state")
        {
                if ($jdata["type"] == "output")
                {
                        setStateOutput($jdata["id"], $jdata["value"]);
                }
                else if ($jdata["type"] == "input")
                {
                        setStateInput($jdata["id"], $jdata["value"]);
                }
                else if ($jdata["type"] == "audio")
                {
                        setStateAudio($jdata["player_id"], $jdata["value"]);
                }
                else if ($jdata["type"] == "camera")
                {
                        setStateCamera($jdata["camera_id"], $jdata["camera_action"], $jdata["value"]);
                }
                else
                        die_error();

                $value = array("cn_user" => $jdata["cn_user"],
                               "success" => true);
                die (json_encode($value));
        }

        if ($jdata["action"] == "poll_listen")
        {
                if ($jdata["type"] == "register")
                        die (json_encode(pollListenRegister()));
                else if ($jdata["type"] == "unregister")
                        die (json_encode(pollListenUnregister($jdata["uuid"])));
                else if ($jdata["type"] == "get")
                        die (json_encode(pollListenGet($jdata["uuid"])));
        }

        if ($jdata["action"] == "get_playlist")
        {
                die (json_encode(getPlaylistArray($jdata)));
        }

        if ($jdata["action"] == "play_file" && isset($uploadfile))
        {
                die (json_encode(playAudioFile($jdata["player_id"], $uploadfile)));
        }

        //Error unknown command/action
        die_error();
?>
