<?php
        //Some utility functions
        require_once "Calaos.php";

        function _image_data_json($url, $type, $width = 0, $height = 0)
        {
                $res = array();
                if ($width == 0 || $height == 0)
                {
                        $fp = @fopen($url, "r");

                        if ($fp === false)
                        {
                                $res["error"] = true;
                                $res["error_str"] = "unable to open url (".$url.")";
                                return $res;
                        }

                        $data = "";
                        while (!feof($fp))
                                $data .= fread($fp, 1024);
                        $res["data"] = base64_encode($data);

                        fclose($fp);
                }
                else
                {
                        $respic = imagecreatetruecolor($width, $height);
                        $src = imagecreatefromjpeg($url);
                        if (!$src)
                        {
                                $res["error"] = true;
                                $res["error_str"] = "unable to open url (".$url.")";
                                imagedestroy($respic);
                                return $res;
                        }

                        if ($type == "png")
                        {
                                imagealphablending($src, false);
                                imagesavealpha($src, true);
                                imagealphablending($respic, false);
                                imagesavealpha($respic, true);
                        }

                        imagecopyresized($respic, $src, 0, 0, 0, 0, $width, $height, imagesx($src), imagesy($src));
                        ob_start();
                        header("Content-type: image/".$type);
                        if ($type == "jpeg")
                                imagejpeg($respic);
                        else
                                imagepng($respic);
                        imagedestroy($respic);
                        imagedestroy($src);
                        $res["data"] = base64_encode(ob_get_clean());
                }
                $res["contenttype"] = "image/".$type;
                $res["encoding"] = "base64";
                return $res;
        }

        function get_camera_pic($_camera_id, $_width = 0, $_height = 0)
        {
                if (isset($_width)) $width = $_width; else $width = 0;
                if (isset($_height)) $height = $_height; else $height = 0;

                $calaos = Calaos::Instance();

                if (isset($_camera_id))
                {
                        $camera_id = $_camera_id;
                        $res = explode(" ", $calaos->SendRequest("camera get ".$camera_id));
                        $val = 0;
                        for ($i = 0;$i < count($res);$i++)
                        {
                                if (ereg ("^jpeg_url", $res[$i]) == true)
                                {
                                        $val = $i;
                                        break;
                                }
                        }
                        strtok(urldecode($res[$val]), ':');
                        $url = strtok('');

                        return _image_data_json($url, 'jpeg', $width, $height);
                }

                $res = array();
                $res["error"] = true;
                $res["error_str"] = "camera_id not set";
                $calaos->Clean();
                return $res;
        }

        function get_cover_pic($_player_id, $_width = 0, $_height = 0)
        {
                if (isset($_width)) $width = $_width; else $width = 0;
                if (isset($_height)) $height = $_height; else $height = 0;

                $calaos = Calaos::Instance();

                if (isset($_player_id))
                {
                        $player_id = $_player_id;
                        $res = explode(" ", $calaos->SendRequest("audio ".$player_id." cover?"));
                        $val = 0;
                        for ($j = 0;$j < count($res);$j++)
                        {
                                list($opt, $val) = explode(":", urldecode($res[$j]), 2);
                                if ($opt == "cover")
                                        $url = urldecode($val);
                        }

                        if (isset($url))
                                return _image_data_json($url, 'png', $width, $height);
                }

                $res = array();
                $res["error"] = true;
                $res["error_str"] = "player_id not set";
                $calaos->Clean();
                return $res;
        }

        //Load an option from the xml config file
        function getConfigOption($attribute)
        {
                $xml = new XMLReader();
                $xml->open("/etc/calaos/local_config.xml");
                while($xml->read())
                {
                        if ($xml->name == "calaos:option")
                        {
                                if ($xml->getAttribute("name") == $attribute)
                                {
                                        $v = $xml->getAttribute("value");
                                        $xml->close();
                                        return $v;
                                }
                        }
                }

                $xml->close();
                return "";
        }

        function setConfigOption($attribute, $value)
        {
                $config = Array();

                $xml = new XMLReader();
                $xml->open("/etc/calaos/local_config.xml");
                $found = false;
                while($xml->read())
                {
                        if ($xml->name == "calaos:option")
                        {
                                $option["name"] = $xml->getAttribute("name");
                                if ($option["name"] == $attribute)
                                {
                                        $option["value"] = $value;
                                        $found = true;
                                }
                                else
                                        $option["value"] = $xml->getAttribute("value");
                                $config[] = $option;
                        }
                }

                //Add the new option
                if (!$found)
                {
                        $option["value"] = $value;
                        $option["name"] = $attribute;
                        $config[] = $option;
                }

                $handle = fopen("/etc/calaos/local_config.xml", "w");
                if (fwrite($handle, '<?xml version="1.0"?>') === false) return false;
                if (fwrite($handle, '<calaos:config xmlns:calaos="http://www.calaos.fr">') === false) return false;
                for ($i = 0;$i < count($config);$i++)
                {
                        $opt = $config[$i];
                        if (fwrite($handle, '<calaos:option name="'.$opt["name"].'" value="'.$opt["value"].'"/>') === false)
                        {
                                return false;
                        }
                }
                if (fwrite($handle, '</calaos:config>') === false) return false;
                fclose($handle);

                return true;
        }

        //User agent parsing for mobile phone detection
        function isMobileDevice()
        {
                if (stristr($_SERVER['HTTP_USER_AGENT'], 'windows') && !stristr($_SERVER['HTTP_USER_AGENT'], 'windows ce'))
                        return false;

                if (preg_match('/(up.browser|up.link|windows ce|iemobile|mmp|symbian|smartphone|midp|wap|phone|pocket|mobile|pda|psp)/i',
                              strtolower($_SERVER['HTTP_USER_AGENT'])))
                        return true;

                if ( (strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/vnd.wap.xhtml+xml') > 0) ||
                     (strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'text/vnd.wap.wml') > 0) ||
                     (isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE'])) ||
                     isset($_SERVER['X-OperaMini-Features']) ||
                     isset($_SERVER['UA-pixels']) )
                        return true;

                $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));
                $mobile_agents = array(
                        'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
                        'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
                        'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
                        'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
                        'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
                        'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
                        'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
                        'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
                        'wapr','webc','winw','winw','xda','xda-');

                if (in_array($mobile_ua,$mobile_agents))
                        return true;

                if (strpos(strtolower($_SERVER['ALL_HTTP']), 'OperaMini') > 0)
                        return true;

                return false;
        }

        //detect iphone/ipod browser
        function isIphone()
        {
                if (stristr($_SERVER['HTTP_USER_AGENT'], "iPhone") || strpos($_SERVER['HTTP_USER_AGENT'], "iPod"))
                        return true;

                return false;
        }

        function getRoomTypeString($room)
        {
                if ($room == "salon") $rname = "Salon";
                if ($room == "lounge") $rname = "Salon";
                if ($room == "chambre") $rname = "Chambre";
                if ($room == "bedroom") $rname = "Chambre";
                if ($room == "cuisine") $rname = "Cuisine";
                if ($room == "kitchen") $rname = "Cuisine";
                if ($room == "bureau") $rname = "Bureau";
                if ($room == "office") $rname = "Bureau";
                if ($room == "sam") $rname = "Salle a manger";
                if ($room == "diningroom") $rname = "Salle a manger";
                if ($room == "cave") $rname = "Cave";
                if ($room == "cellar") $rname = "Cave";
                if ($room == "divers") $rname = "Divers";
                if ($room == "various") $rname = "Divers";
                if ($room == "misc") $rname = "Divers";
                if ($room == "exterieur") $rname = "Exterieur";
                if ($room == "outside") $rname = "Exterieur";
                if ($room == "sdb") $rname = "Salle de bain";
                if ($room == "bathroom") $rname = "Salle de bain";
                if ($room == "hall") $rname = "Couloir";
                if ($room == "couloir") $rname = "Couloir";
                if ($room == "corridor") $rname = "Couloir";
                if ($room == "garage") $rname = "Garage";
                if ($room == "Internal") $rname = "Internal Room";

                else $rname == "Unknown!";

                return $rname;
        }

        function getRoomTypeIcon($room)
        {
                if ($room == "salon") $rname = "room_salon.png";
                if ($room == "lounge") $rname = "room_salon.png";
                if ($room == "chambre") $rname = "room_chambre.png";
                if ($room == "bedroom") $rname = "room_chambre.png";
                if ($room == "cuisine") $rname = "room_cuisine.png";
                if ($room == "kitchen") $rname = "room_cuisine.png";
                if ($room == "bureau") $rname = "room_bureau.png";
                if ($room == "office") $rname = "room_bureau.png";
                if ($room == "sam") $rname = "room_sam.png";
                if ($room == "diningroom") $rname = "room_sam.png";
                if ($room == "cave") $rname = "room_cave.png";
                if ($room == "cellar") $rname = "room_cave.png";
                if ($room == "divers") $rname = "room_misc.png";
                if ($room == "various") $rname = "room_misc.png";
                if ($room == "misc") $rname = "room_misc.png";
                if ($room == "exterieur") $rname = "room_exterieur.png";
                if ($room == "outside") $rname = "room_exterieur.png";
                if ($room == "sdb") $rname = "room_sdb.png";
                if ($room == "bathroom") $rname = "room_sdb.png";
                if ($room == "hall") $rname = "room_hall.png";
                if ($room == "couloir") $rname = "room_hall.png";
                if ($room == "corridor") $rname = "room_hall.png";
                if ($room == "garage") $rname = "room_garage.png";
                if ($room == "Internal") $rname = "room.png";

                else $rname == "room.png";

                if (!file_exists("img/".$rname) && !file_exists("../img/".$rname))
                        $rname = "room.png";

                return $rname;
        }

        function isSSHRunning()
        {
                $prog = "dropbear";
                exec("ps | grep $prog | grep -v grep", $pids);

                if (count($pids) > 0)
                        return true;
                else
                        return false;
        }

        function StopSSHDaemon()
        {
                shell_exec("start-stop-daemon --stop --quiet --pidfile /var/run/dropbear.pid");
                shell_exec("killall -9 dropbear"); //To be sure
        }

        function StartSSHDaemon()
        {
                shell_exec("/etc/init.d/21_dropbear");
        }

        function getTooltip($iotype, $id)
        {
                $ctype = get_io($iotype, $id, "type");
                $vtype = get_io($iotype, $id, "var_type");

                if ($vtype == "bool")
                {
                        $tooltip = "<b><em>Valeurs possibles:</em></b><br/>true <em>(activ&eacute;)</em><br />false <em>(d&eacute;sactiv&eacute;)</em>";
                        if ($iotype == "input")
                                $tooltip .= "<br />changed <em>(n'importe quel etat)</em>";
                        if ($iotype == "output")
                                $tooltip .= "<br />toggle <em>(intervertir)</em><br />impulse &lt;temps&gt; <em>(donne une impulsion de n millisecondes)</em>";
                }
                else if ($vtype == "float")
                {
                        if ($ctype == "WIDigitalTriple")
                                $tooltip = "<b><em>Valeurs possibles:</em></b><br/>1 <em>1 appui</em><br/>2 <em>2 appuis</em><br/>3 <em>3 appuis</em>";
                        if ($iotype == "output")
                                $tooltip = "<b><em>Valeurs possibles:</em></b><br/>Valeurs num&eacute;riques<br/>inc &lt;n&gt; <em>incremente de n valeur</em><br/>dec &lt;n&gt; <em>decremente de n valeur</em>";
                        else
                                $tooltip = "<b><em>Valeurs possibles:</em></b><br/>Valeurs num&eacute;riques<br />changed <em>(n'importe quel etat)</em>";
                }
                else if ($vtype == "string")
                {
                        if ($ctype == "AudioInput")
                                $tooltip = "<b><em>Valeurs possibles:</em></b><br/>onplay <em>(le lecteur se met en lecture)</em><br/>onpause <em>(le lecteur se met en pause)</em><br/>onstop <em>(le lecteur s'arrete)</em><br/>onsongchange <em>(le lecteur change de piste)</em><br/>onerror <em>(le lecteur emet une erreur)</em><br />changed <em>(n'importe quel etat)</em>";
                        else if ($ctype == "IRInput")
                                $tooltip = "<b><em>Valeurs possibles:</em></b><br/>&lt;telecommande&gt;.&lt;commande&gt;<br />changed <em>(n'importe quel etat)</em>";
                        else if ($ctype == "AudioOutput")
                        {
                                $tooltip = "<b><em>Valeurs possibles:</em></b><br/>" .
                                        "play <em>(Lecture)</em><br/>" .
                                        "pause <em>(Pause)</em><br/>" .
                                        "stop <em>(Stop)</em><br/>" .
                                        "next <em>(Piste suivante)</em><br/>" .
                                        "previous <em>(Piste precedente)</em><br/>" .
                                        "power on <em>(Allume le lecteur)</em><br/>" .
                                        "power off <em>(Eteint le lecteur)</em><br/>" .
                                        "sleep &lt;seconds&gt; <em>(Eteint dans n secondes)</em><br/>" .
                                        "sync &lt;playerid&gt; <em>(Synchronise avec le lecteur playerid)</em><br/>" .
                                        "unsync &lt;playerid&gt; <em>(Desynchronise avec le lecteur playerid)</em><br />" .
                                        "play &lt;type:id&gt; <em>(Vide la liste de lecture et la remplace par l'element id)</em><br/>" .
                                        "add &lt;type:id&gt; <em>(Ajoute l'element id a la liste de lecture)</em><br/>" .
                                        " -> type peut etre <em>track_id, album_id, artist_id, genre_id, year, playlist_id, folder_id, radio_id</em><br />";
                        }
                        else if ($ctype == "CamOutput")
                        {
                                $tooltip = "<b><em>Valeurs possibles:</em></b><br/>" .
                                        "recall &lt;position&gt; <em>(Rappelle une position enregistr&eacute;)</em><br/>" .
                                        "save &lt;position&gt; <em>(Sauvegarde la position)</em><br/>" .
                                        "move up <em>(Deplace la camera)</em><br/>" .
                                        "move left <em>(Deplace la camera)</em><br/>" .
                                        "move right <em>(Deplace la camera)</em><br/>" .
                                        "move down <em>(Deplace la camera)</em><br/>" .
                                        "move home <em>(Deplace la camera)</em>";
                        }
                        else if ($ctype == "IROutput")
                                $tooltip = "<b><em>Valeurs possibles:</em></b><br/>send &lt;telecommande&gt; &lt;commande&gt;";
                        else if ($ctype == "OutTouchscreen")
                                $tooltip = "<b><em>Valeurs possibles:</em></b><br/>show,cam,0 <em>(Affiche la camera 0)</em><br/>show,cam,1 <em>(Affiche la camera 1)</em><br/>show,cam,2 <em>(Affiche la camera 2)</em><br/>...";
                        else if ($ctype == "WODali" || $ctype == "X10Output"  || $ctype == "WONeon")
                        {
                                $tooltip = "<b><em>Valeurs possibles:</em></b><br/>" .
                                        "set &lt;pourcent&gt; <em>(Met la lampe a x pourcent)</em><br/>" .
                                        "set off &lt;pourcent&gt; <em>(Fixe la valeur de la lampe a x pourcent sans l'allumer)</em><br/>" .
                                        "up &lt;pourcent&gt; <em>(Augmente l'intensit&eacute; de x pourcent)</em><br/>" .
                                        "down &lt;pourcent&gt; <em>(Baisse l'intensit&eacute; de x pourcent)</em><br/>" .
                                        "on <em>(Allume la lumiere)</em><br/>" .
                                        "true <em>(Allume la lumiere)</em><br/>" .
                                        "off <em>(Eteint la lumiere)</em><br/>" .
                                        "false <em>(Eteint la lumiere)</em><br/>";
                                        "toggle <em>(Inverse l'etat de la lumiere)</em><br/>";
                        }
                        else if ($ctype == "WODaliRVB")
                        {
                                $tooltip = "<b><em>Valeurs possibles:</em></b><br/>" .
                                        "set &lt;couleur&gt; <em>(Met la lampe a la couleur demand&eacute; [0-65535])</em><br/>" .
                                        "on <em>(Allume la lumiere)</em><br/>" .
                                        "true <em>(Allume la lumiere)</em><br/>" .
                                        "off <em>(Eteint la lumiere)</em><br/>" .
                                        "false <em>(Eteint la lumiere)</em><br/>";
                                        "toggle <em>(Inverse l'etat de la lumiere)</em><br/>";
                                        "up_red &lt;pourcent&gt; <em>(Augmente l'intensit&eacute; de x pourcent de rouge)</em><br/>" .
                                        "down_red &lt;pourcent&gt; <em>(Baisse l'intensit&eacute; de x pourcent de rouge)</em><br/>" .
                                        "set_red &lt;pourcent&gt; <em>(Met le rouge a la couleur demand&eacute;)</em><br/>" .
                                        "up_green &lt;pourcent&gt; <em>(Augmente l'intensit&eacute; de x pourcent de vert)</em><br/>" .
                                        "down_green &lt;pourcent&gt; <em>(Baisse l'intensit&eacute; de x pourcent de vert)</em><br/>" .
                                        "set_green &lt;pourcent&gt; <em>(Met le vert a la couleur demand&eacute;)</em><br/>" .
                                        "up_blue &lt;pourcent&gt; <em>(Augmente l'intensit&eacute; de x pourcent de bleu)</em><br/>" .
                                        "down_blue &lt;pourcent&gt; <em>(Baisse l'intensit&eacute; de x pourcent de bleu)</em><br/>" .
                                        "set_blue &lt;pourcent&gt; <em>(Met le bleu a la couleur demand&eacute;)</em>";
                        }
                        else if ($ctype == "WOVolet")
                        {
                                $tooltip = "<b><em>Valeurs possibles:</em></b><br/>" .
                                        "up <em>(Monte le volet)</em><br/>" .
                                        "down <em>(Descend le volet)</em><br/>" .
                                        "stop <em>(Arrete le volet)</em><br/>" .
                                        "toggle <em>(Inverse l'etat)</em><br/>" .
                                        "impulse up &lt;temps&gt; <em>(Donne une impulsion sur la montee de x millisecondes)</em><br/>" .
                                        "impulse down &lt;temps&gt; <em>(Donne une impulsion sur la descente de x millisecondes)</em>";
                        }
                        else if ($ctype == "WOVoletSmart")
                        {
                                $tooltip = "<b><em>Valeurs possibles:</em></b><br/>" .
                                        "up <em>(Monte le volet)</em><br/>" .
                                        "down <em>(Descend le volet)</em><br/>" .
                                        "stop <em>(Arrete le volet)</em><br/>" .
                                        "toggle <em>(Inverse l'etat)</em><br/>" .
                                        "impulse up &lt;temps&gt; <em>(Donne une impulsion sur la montee de x millisecondes)</em><br/>" .
                                        "impulse down &lt;temps&gt; <em>(Donne une impulsion sur la descente de x millisecondes)</em><br/>" .
                                        "set &lt;position&gt; <em>(Met le volet a la position demand&eacute; [0-100])</em><br/>" .
                                        "up &lt;position&gt; <em>(monte le volet de X pourcent)</em><br/>" .
                                        "down &lt;position&gt; <em>(descend le volet de X pourcent)</em><br/>" .
                                        "calibrate <em>(Lance la calibration du volet)</em>";
                        }
                        else
                        {
                                $tooltip = "<b><em>Valeurs possibles:</em></b><br/>Chaines de caracteres";
                                if ($iotype == "input")
                                        $tooltip .= "<br />changed <em>(n'importe quel etat)</em>";
                        }
                }

                return $tooltip;
        }
?>
