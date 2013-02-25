<?php
        require_once("Utils.php");
        require_once "Calaos.php";

        function getHomeArray()
        {
                $home = array();

                $calaos = Calaos::Instance();

                $rooms = array();

                //HOME
                $res = explode(" ", $calaos->SendRequest("home ?"));

                for ($i = 1;$i < count($res);$i++)
                {
                        list($room_type, $count) = explode(":", urldecode($res[$i]), 2);

                        $res2 = explode(" ", $calaos->SendRequest("home get " . $room_type));
                        list($str, $countr) = explode(":", urldecode($res2[1]), 2);

                        if ($countr <= 0) continue;

                        $room = array();
                        $room["type"] = $room_type;
                        $oldid = 0;

                        for ($j = 2;$j < count($res2);$j++)
                        {
                                list($room_id, $opt, $value) = explode(":", urldecode($res2[$j]), 3);

                                if ($room_id != $oldid)
                                {
                                        $rooms[] = $room;

                                        $room = array();
                                        $room["type"] = $room_type;

                                        $oldid = $room_id;
                                }

                                $room[$opt] = $value;

                                if ($opt == "hits")
                                {
                                        $room["items"] = getIOArray($calaos, $room_type, $room_id);
                                }
                        }

                        $rooms[] = $room;
                }

                $home["home"] = $rooms;

                //CAMERA
                $res = explode(" ", $calaos->SendRequest("camera ?"));
                $nb = urldecode($res[1]);

                $cameras = array();

                for ($i = 0;$i < $nb;$i++)
                {
                        $camera = array();

                        $res = explode(" ", $calaos->SendRequest("camera get ".$i));

                        for ($j = 0;$j < count($res);$j++)
                        {
                                list($opt, $val) = explode(":", urldecode($res[$j]), 2);
                                if ($opt == "name")
                                        $camera["name"] = urldecode($val);
                                if ($opt == "ptz")
                                        $camera["ptz"] = $val;
                        }

                        $camera["url_lowres"] = "/camera.php?camera_id=$i&width=300&height=225";
                        $camera["url_highres"] = "/camera.php?camera_id=$i&width=640&height=480";

                        $cameras[] = $camera;
                }

                $home["cameras"] = $cameras;

                //AUDIO
                $res = explode(" ", $calaos->SendRequest("audio ?"));
                $nb = urldecode($res[1]);

                $audios = array();

                for ($i = 0;$i < $nb;$i++)
                {
                        $audio = array();

                        $res = explode(" ", $calaos->SendRequest("audio get ".$i));

                        for ($j = 0;$j < count($res);$j++)
                        {
                                list($opt, $val) = explode(":", urldecode($res[$j]), 2);
                                if ($opt == "name")
                                        $audio["name"] = urldecode($val);
                                if ($opt == "playlist")
                                        $audio["playlist"] = $val;
                                if ($opt == "database")
                                        $audio["database"] = $val;
                        }

                        $audio["player_id"] = $i;

                        $value = getAudioInfoArray($calaos, $i, "volume?");
                        $audio["volume"] = $value["volume"];

                        $value = getAudioInfoArray($calaos, $i, "time?");
                        $audio["time_elapsed"] = $value["time"];

                        $value = getAudioInfoArray($calaos, $i, "status?");
                        $audio["status"] = $value["status"];

                        $audio["cover_url"] = "/music.php?player_id=$i";

                        $audio["current_track"] = getAudioInfoArray($calaos, $i, "songinfo?");

                        $res2 = explode(" ", $calaos->SendRequest("audio " . $i . " playlist current?"));
                        $audio["playlist_current_track"] = urldecode($res2[3]);

                        $res2 = explode(" ", $calaos->SendRequest("audio " . $i . " playlist size?"));
                        $audio["playlist_size"] = urldecode($res2[3]);

                        $audios[] = $audio;
                }

                $home["audio"] = $audios;

                $calaos->Clean();

                return $home;
        }

        function getIOArray($calaos, $room_type, $room_id)
        {
                $room_items = array();

                //Load IOs
                $res = explode(" ", $calaos->SendRequest("room " . $room_type . " get " . $room_id));
                for ($i = 1;$i < count($res);$i++)
                {
                        list($opt, $val) = explode(":", urldecode($res[$i]), 2);

                        if ($opt == "input")
                                $inputs[] = getInputArray($calaos, $val);
                        else if ($opt == "output")
                                $outputs[] = getOutputArray($calaos, $val);
                }

                $room_items["inputs"] = $inputs;
                $room_items["outputs"] = $outputs;

                return $room_items;
        }

        function getInputArray($calaos, $id)
        {
                $input = array();

                $res = explode(" ", $calaos->SendRequest("input " . $id . " get"));
                for ($j = 1;$j < count($res);$j++)
                {
                        list($opt, $val) = explode(":", urldecode($res[$j]), 2);

                        if (!empty($opt))
                                $input[$opt] = urldecode($val);
                }

                return $input;
        }

        function getOutputArray($calaos, $id)
        {
                $output = array();

                $res = explode(" ", $calaos->SendRequest("output " . $id . " get"));
                for ($j = 1;$j < count($res);$j++)
                {
                        list($opt, $val) = explode(":", urldecode($res[$j]), 2);

                        if (!empty($opt))
                                $output[$opt] = urldecode($val);
                }

                return $output;
        }

        function getAudioInfoArray($calaos, $player_id, $request)
        {
                $info = array();

                $res = explode(" ", $calaos->SendRequest("audio " . $player_id . " " . $request));
                for ($j = 2;$j < count($res);$j++)
                {
                        list($opt, $val) = explode(":", urldecode($res[$j]), 2);

                        if (!empty($opt))
                                $info[$opt] = urldecode($val);
                }

                return $info;
        }

        function getStatusArray($inputs, $outputs, $audio_players)
        {
                $calaos = Calaos::Instance();
                $result_in = array();
                $result_out = array();

                if (is_array($inputs))
                {
                        foreach ($inputs as $id)
                        {
                                $res = explode(" ", $calaos->SendRequest("input " . $id . " state?"));
                                $result_in[$id] = urldecode($res[2]);
                        }
                }

                if (is_array($outputs))
                {
                        foreach ($outputs as $id)
                        {
                                $res = explode(" ", $calaos->SendRequest("output " . $id . " state?"));
                                $result_out[$id] = urldecode($res[2]);
                        }
                }

                if (is_array($audio_players))
                {
                        foreach ($audio_players as $id)
                        {
                                $player = array();

                                $res = explode(" ", $calaos->SendRequest("audio get ".$id));

                                if (count($res) > 3)
                                {
                                        $player["player_id"] = $id;

                                        $value = getAudioInfoArray($calaos, $id, "volume?");
                                        $player["volume"] = $value["volume"];

                                        $value = getAudioInfoArray($calaos, $id, "time?");
                                        $player["time_elapsed"] = $value["time"];

                                        $value = getAudioInfoArray($calaos, $id, "status?");
                                        $player["status"] = $value["status"];

                                        $player["cover_url"] = "/music.php?player_id=$id";

                                        $player["current_track"] = getAudioInfoArray($calaos, $id, "songinfo?");

                                        $res2 = explode(" ", $calaos->SendRequest("audio " . $id . " playlist current?"));
                                        $player["playlist_current_track"] = urldecode($res2[3]);

                                        $res2 = explode(" ", $calaos->SendRequest("audio " . $id . " playlist size?"));
                                        $player["playlist_size"] = urldecode($res2[3]);

                                        $result_audio[$id] = $player;
                                }
                                else
                                {
                                        $player["error"] = "player not found";
                                }
                        }
                }

                $calaos->Clean();

                return array("inputs" => $result_in, "outputs" => $result_out, "audio_players" => $result_audio);
        }

        function setStateOutput($id, $value)
        {
                $calaos = Calaos::Instance();

                $calaos->SendRequest("output ". rawurlencode($id) ." set ". rawurlencode($value));

                $calaos->Clean();
        }

        function setStateInput($id, $value)
        {
                $calaos = Calaos::Instance();

                $calaos->SendRequest("input ". rawurlencode($id) ." set ". rawurlencode($value));

                $calaos->Clean();
        }

        function getPlaylistArray($jdata)
        {
                $player_id = $jdata["player_id"];

                $calaos = Calaos::Instance();

                $playlist = array();

                $res = explode(" ", $calaos->SendRequest("audio " . $player_id . " playlist size?"));
                $nb = urldecode($res[3]);
                $playlist["count"] = $nb;

                if (isset($jdata["from"]))
                {
                        $from = $jdata["from"];
                        if ($from < 0 || $from >= $nb)
                                $from = 0;
                }
                else
                {
                        $from = 0;
                }

                if (isset($jdata["to"]))
                {
                        $to = $jdata["to"];
                        if ($to < $from)
                                $to = $from;
                        if ($to >= $nb)
                                $to = $nb - 1;
                }
                else
                {
                        $to = $nb - 1;
                }

                $res = explode(" ", $calaos->SendRequest("audio " . $player_id . " playlist current?"));
                $playlist["current_track"] = urldecode($res[3]);

                $playlist["items"] = array();

                $playlist["from"] = $from;
                $playlist["to"] = $to;

                for ($i = $from;$i < $to + 1;$i++)
                {
                        $res = explode(" ", $calaos->SendRequest("audio " . $player_id . " playlist " . $i . " getitem?"));

                        $track = array();
                        for ($j = 4;$j < count($res);$j++)
                        {
                                list($opt, $val) = explode(":", urldecode($res[$j]), 2);

                                if (!empty($opt))
                                        $track[$opt] = urldecode($val);
                        }

                        $playlist["items"][] = $track;
                }

                $calaos->Clean();

                return $playlist;
        }

        function setStateAudio($player_id, $value)
        {
                $calaos = Calaos::Instance();

                $calaos->SendRequest("audio " . rawurlencode($player_id) . " " . $value);

                $calaos->Clean();
        }

        function setStateCamera($camera_id, $camera_action, $value)
        {
                $calaos = Calaos::Instance();

                $calaos->SendRequest("camera " . $camera_action . " " . rawurlencode($camera_id) . " " . $value);

                $calaos->Clean();
        }

        function pollListenRegister()
        {
                $calaos = Calaos::Instance();

                $res = explode(" ", $calaos->SendRequest("poll_listen register"));
                $calaos->Clean();

                $result = array();
                $result["uuid"] = urldecode($res[2]);

                return $result;
        }

        function pollListenUnregister($uuid)
        {
                $calaos = Calaos::Instance();

                $res = explode(" ", $calaos->SendRequest("poll_listen unregister " . $uuid));
                $calaos->Clean();

                $result = array();
                $result["success"] = urldecode($res[2]);

                return $result;
        }

        function pollListenGet($uuid)
        {
                $calaos = Calaos::Instance();

                $res = explode(" ", $calaos->SendRequest("poll_listen get " . $uuid));
                $calaos->Clean();

                $result = array();

                if (urldecode($res[2] == "error"))
                {
                        $result["success"] = "false";
                        return $result;
                }

                $result["success"] = "true";
                $result["events"] = array();

                for ($i = 2;$i < count($res);$i++)
                {
                        $tab = explode(":", urldecode($res[$i]));

                        for ($j = 0;$j < count($tab);$j++)
                        {
                                if ($j == 0)
                                        $str = $tab[$j];
                                else
                                        $str .= $tab[$j];

                                if ($j < count($tab) - 1)
                                        $str .= " ";
                        }

                        $result["events"][] = $str;
                }

                return $result;
        }

        function playAudioFile($player_id, $file)
        {
                $oggfile = "/tmp/public_web/calaos_message.ogg";
                copy($file, $oggfile);
                @unlink($file);

                $calaos = Calaos::Instance();
                $res = explode(" ", $calaos->SendRequest("audio " . $player_id . " playlist clear"));
                $res = explode(" ", $calaos->SendRequest("audio " . $player_id . " playlist play http://".$_SERVER["SERVER_ADDR"]."/public/calaos_message.ogg"));
                $calaos->Clean();

                $result = array();
                $result["success"] = "false";
                return $result;
        }
?>