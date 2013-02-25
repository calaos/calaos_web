<?php
        //Check user identity
        require "auth.php";

        require_once "Calaos.php";
        require_once "populate_select.php";
        require_once "Utils.php";

        $calaos = Calaos::Instance();

        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

        $action = stripslashes(@$_GET['action']);
        $id = stripslashes(@$_GET['id']);
        $opt = stripslashes(@$_GET['opt']);
        $value = stripslashes(@$_GET['value']);
        $room_type = stripslashes(@$_GET['room_type']);
        $room_id = stripslashes(@$_GET['room_id']);
        $param1 = stripslashes(@$_GET['param1']);
        $param2 = stripslashes(@$_GET['param2']);
        $param3 = stripslashes(@$_GET['param3']);
        $param4 = stripslashes(@$_GET['param4']);
        $param5 = stripslashes(@$_GET['param5']);
        $param6 = stripslashes(@$_GET['param6']);
        $param7 = stripslashes(@$_GET['param7']);
        $param8 = stripslashes(@$_GET['param8']);
        $param9 = stripslashes(@$_GET['param9']);
        $param10 = stripslashes(@$_GET['param10']);
        $param11 = stripslashes(@$_GET['param11']);
        $param12 = stripslashes(@$_GET['param12']);
        $param13 = stripslashes(@$_GET['param13']);

        if (!isset($action))
                exit(0);

        if($action == "save_standard")
        {
                $calaos->SendRequest("save");
        }
        else if($action == "save_default")
        {
                $calaos->SendRequest("save default");
        }
        else if ($action == "output")
        {
                $calaos->SendRequest("output ". rawurlencode($id) ." set ". rawurlencode($value));
        }

        else if ($action == "input")
        {
                $calaos->SendRequest("input ". rawurlencode($id) ." set ". rawurlencode($value));
        }

        else if ($action == "output_param")
        {
                $calaos->SendRequest("output ". rawurlencode($id) ." set_param ". rawurlencode($opt) . " " . rawurlencode($value));

                //force a refresh of the io cache
                $_SESSION['input_changed'] = "true";
                $_SESSION['output_changed'] = "true";
        }

        else if ($action == "input_param")
        {
                $calaos->SendRequest("input ". rawurlencode($id) ." set_param ". rawurlencode($opt) . " " . rawurlencode($value));

                //force a refresh of the io cache
                $_SESSION['input_changed'] = "true";
                $_SESSION['output_changed'] = "true";
        }

        else if ($action == "output_delparam")
        {
                $calaos->SendRequest("output ". rawurlencode($id) ." delete_param ". rawurlencode($opt));

                //force a refresh of the io cache
                $_SESSION['input_changed'] = "true";
                $_SESSION['output_changed'] = "true";
        }

        else if ($action == "input_delparam")
        {
                $calaos->SendRequest("input ". rawurlencode($id) ." delete_param ". rawurlencode($opt));

                //force a refresh of the io cache
                $_SESSION['input_changed'] = "true";
                $_SESSION['output_changed'] = "true";
        }

        else if ($action == "update")
        {
                $res = explode(" ", $calaos->SendRequest("output ". rawurlencode($id) ." state?"));
                echo $id." ".$res[2];
        }

        else if ($action == "edit_room")
        {
                if ($room_id != "" && $opt != "" && $value != "" && $room_type != "")
                {
                        if ($opt == "room_name")
                                $option = "name";
                        else if ($opt == "room_type")
                                $option = "type";
                        else if ($opt == "room_hits")
                                $option = "hits";
                        $res = $calaos->SendRequest("room ". rawurlencode($room_type) ." set ". rawurlencode($room_id) ." ". rawurlencode($option) ." ". rawurlencode($value));

                        //force a refresh of the io cache
                        $_SESSION['input_changed'] = "true";
                        $_SESSION['output_changed'] = "true";
                }
        }

        else if ($action == "add_room")
        {
                if ($room_id != "" && $room_type != "")
                {
                        $calaos->SendRequest("room add ". rawurlencode($room_type) ." ". rawurlencode($room_id));

                        //force a refresh of the io cache
                        $_SESSION['input_changed'] = "true";
                        $_SESSION['output_changed'] = "true";
                }
        }

        else if ($action == "delete_room")
        {
                $calaos->SendRequest("room ". rawurlencode($room_type) ." delete ". rawurlencode($room_id));

                //force a refresh of the io cache
                $_SESSION['input_changed'] = "true";
                $_SESSION['output_changed'] = "true";
        }

        else if ($action == "delete_input")
        {
                $calaos->SendRequest("input ". rawurlencode($value) ." delete");

                //force a refresh of the io cache
                $_SESSION['input_changed'] = "true";
                $_SESSION['output_changed'] = "true";
        }

        else if ($action == "delete_output")
        {
                $calaos->SendRequest("output ". rawurlencode($value) ." delete");

                //force a refresh of the io cache
                $_SESSION['input_changed'] = "true";
                $_SESSION['output_changed'] = "true";
        }

        else if ($action == "camera_recall")
        {
                $calaos->SendRequest("camera move ". rawurlencode($id) ." ". rawurlencode($value));
        }

        else if ($action == "camera_save")
        {
                $calaos->SendRequest("camera save ". rawurlencode($id) ." ". rawurlencode($value));
        }

        else if ($action == "camera_move")
        {
                $calaos->SendRequest("camera move ". rawurlencode($id) ." ". rawurlencode($value));
        }

        else if ($action == "add_plage")
        {
                if ($opt != "" && $id != "" && $param1 != "" && $param2 != "")
                {
                        $calaos->SendRequest("input ". rawurlencode($id) ." plage add ". rawurlencode($opt) ." ". rawurlencode($param1) ." ". rawurlencode($param2));

                        //force a refresh of the io cache
                        $_SESSION['input_changed'] = "true";
                        $_SESSION['output_changed'] = "true";
                }
        }

        else if ($action == "del_plage")
        {
                if ($opt != "" && $id != "" && $value != "")
                {
                        $calaos->SendRequest("input ". rawurlencode($id) ." plage delete ". rawurlencode($opt) ." ". rawurlencode($value));

                        //force a refresh of the io cache
                        $_SESSION['input_changed'] = "true";
                        $_SESSION['output_changed'] = "true";
                }
        }

        else if ($action == "set_plage")
        {
                if ($opt != "" && $id != "" && $param1 != "" && $param2 != "" && $value != "")
                {
                        $calaos->SendRequest("input ". rawurlencode($id) ." plage set ". rawurlencode($opt) ." ". rawurlencode($value) ." ". rawurlencode($param1) ." ". rawurlencode($param2));

                        //force a refresh of the io cache
                        $_SESSION['input_changed'] = "true";
                        $_SESSION['output_changed'] = "true";
                }
        }

        else if ($action == "create_wago" && $room_type != "" && $room_id != "" && $value != "")
        {
                echo $value." --> ".rawurlencode($value);
                echo "<br/>";
                echo urldecode($value);
                echo "<br/>".$param1." --> ".rawurlencode($param1);
                echo "<br/>".$param2." --> ".rawurlencode($param2);
                echo "<br/>".$param3." --> ".rawurlencode($param3);
                echo "<br/>".$param4." --> ".rawurlencode($param4);
                list($t, $type) = explode(":", urldecode($value), 2);
                if ($type == "WIDigital" || $type == "WIDigitalBP" || $type == "WIDigitalTriple" || $type == "scenario" || $type == "WITemp" || $type == "OWTemp")
                        $calaos->SendRequest("room ".rawurlencode($room_type)." ".rawurlencode($room_id)." create input ".rawurlencode($value)." ".rawurlencode($param1)." ".rawurlencode($param2)." ".rawurlencode($param3)." ".rawurlencode($param4));
                else
                        $calaos->SendRequest("room ".rawurlencode($room_type)." ".rawurlencode($room_id)." create output ".rawurlencode($value)." ".rawurlencode($param1)." ".rawurlencode($param2)." ".rawurlencode($param3)." ".rawurlencode($param4)." ".rawurlencode($param5)." ".rawurlencode($param6)." ".rawurlencode($param7)." ".rawurlencode($param8)." ".rawurlencode($param9)." ".rawurlencode($param10)." ".rawurlencode($param11)." ".rawurlencode($param12)." ".rawurlencode($param13));

                //force a refresh of the io cache
                $_SESSION['input_changed'] = "true";
                $_SESSION['output_changed'] = "true";
        }

        else if ($action == "create_camera" && $room_type != "" && $room_id != "" && $value != "")
        {
                $calaos->SendRequest("room ".rawurlencode($room_type)." ".rawurlencode($room_id)." create camera ".rawurlencode($value)." ".rawurlencode($param1)." ".rawurlencode($param2)." ".rawurlencode($param3)." ".rawurlencode($param4)." ".rawurlencode($param5)." ".rawurlencode($param6));

                //force a refresh of the io cache
                $_SESSION['input_changed'] = "true";
                $_SESSION['output_changed'] = "true";
        }

        else if ($action == "create_ir" && $room_type != "" && $room_id != "" && $value != "")
        {
                $calaos->SendRequest("room ".rawurlencode($room_type)." ".rawurlencode($room_id)." create ir ".rawurlencode($value)." ".rawurlencode($param1)." ".rawurlencode($param2)." ".rawurlencode($param3));

                //force a refresh of the io cache
                $_SESSION['input_changed'] = "true";
                $_SESSION['output_changed'] = "true";
        }

        else if ($action == "create_internal" && $room_type != "" && $room_id != "" && $value != "")
        {
                $calaos->SendRequest("room ".rawurlencode($room_type)." ".rawurlencode($room_id)." create internal ".rawurlencode($value)." ".rawurlencode($param1));

                //force a refresh of the io cache
                $_SESSION['input_changed'] = "true";
                $_SESSION['output_changed'] = "true";
        }

        else if ($action == "create_x10" && $room_type != "" && $room_id != "" && $value != "")
        {
                $calaos->SendRequest("room ".rawurlencode($room_type)." ".rawurlencode($room_id)." create output ".rawurlencode($value)." ".rawurlencode($param1)." ".rawurlencode($param2));

                //force a refresh of the io cache
                $_SESSION['input_changed'] = "true";
                $_SESSION['output_changed'] = "true";
        }

        else if ($action == "create_audio" && $room_type != "" && $room_id != "" && $value != "")
        {
                $calaos->SendRequest("room ".rawurlencode($room_type)." ".rawurlencode($room_id)." create audio ".rawurlencode($value)." ".rawurlencode($param1)." ".rawurlencode($param2)." ".rawurlencode($param3)." ".rawurlencode($param4));

                //force a refresh of the io cache
                $_SESSION['input_changed'] = "true";
                $_SESSION['output_changed'] = "true";
        }

        else if ($action == "create_time" && $room_type != "" && $room_id != "" && $value != "")
        {
                if ($value == "InputTime" || $value == "InputTimer")
                        $calaos->SendRequest("room ".rawurlencode($room_type)." ".rawurlencode($room_id)." create input ".rawurlencode($value)." ".rawurlencode($param1)
                                                ." ".rawurlencode($param2)." ".rawurlencode($param3)." ".rawurlencode($param4)." ".rawurlencode($param5));
                else
                        $calaos->SendRequest("room ".rawurlencode($room_type)." ".rawurlencode($room_id)." create input ".rawurlencode($value)." ".rawurlencode($param1)
                                                ." ".rawurlencode($param2)." ".rawurlencode($param3)." ".rawurlencode($param4)
                                                ." ".rawurlencode($param5)." ".rawurlencode($param6)." ".rawurlencode($param7));

                //force a refresh of the io cache
                $_SESSION['input_changed'] = "true";
                $_SESSION['output_changed'] = "true";
        }

        else if ($action == "create_plage" && $room_type != "" && $room_id != "" && $value != "")
        {
                $calaos->SendRequest("room ".rawurlencode($room_type)." ".rawurlencode($room_id)." create input ".rawurlencode($value)." ".rawurlencode($param1));

                //force a refresh of the io cache
                $_SESSION['input_changed'] = "true";
                $_SESSION['output_changed'] = "true";
        }

        else if ($action == "delete_rule" && $param1 != "" && $id != "")
        {
                $calaos->SendRequest("rules ".rawurlencode($param1)." delete ".($id));
        }

        else if ($action == "delete_condition" && $param1 != "" && $id != "" && $param2 != "")
        {
                $calaos->SendRequest("rules ".rawurlencode($param1)." delete ".rawurlencode($id)." condition ".rawurlencode($param2));
        }

        else if ($action == "delete_action" && $param1 != "" && $id != "" && $param2 != "")
        {
                $calaos->SendRequest("rules ".rawurlencode($param1)." delete ".rawurlencode($id)." action ".rawurlencode($param2));
        }

        else if ($action == "set_condition" && $opt != "" && $id != "" && $value != "")
        {
                $calaos->SendRequest("rules ".rawurlencode($opt)." set ".rawurlencode($id)." condition ".rawurlencode($value)." ".rawurlencode($param1)." ".rawurlencode($param2)." ".rawurlencode($param3)." ".rawurlencode($param4));
        }

        else if ($action == "set_action" && $opt != "" && $id != "" && $value != "")
        {
                $calaos->SendRequest("rules ".rawurlencode($opt)." set ".rawurlencode($id)." action ".rawurlencode($value)." ".rawurlencode($param1)." ".rawurlencode($param2)." ".rawurlencode($param3));
        }

        else if ($action == "add_condition" && $opt != "" && $id != "")
        {
                $calaos->SendRequest("rules ".rawurlencode($opt)." add ".rawurlencode($id)." condition ".rawurlencode($param1)." ".rawurlencode($param2)." ".rawurlencode($param3)." ".rawurlencode($param4));
        }

        else if ($action == "add_action" && $opt != "" && $id != "")
        {
                $calaos->SendRequest("rules ".rawurlencode($opt)." add ".rawurlencode($id)." action ".rawurlencode($param1)." ".rawurlencode($param2)." ".rawurlencode($param3));
        }

        else if ($action == "new_rule" && $opt != "" && $value != "")
        {
                $calaos->SendRequest("rules add ".rawurlencode($opt)." ".rawurlencode($value)." ".rawurlencode($param1)." ".rawurlencode($param2)." ".rawurlencode($param3)." ".rawurlencode($param4)." ".rawurlencode($param5)." ".rawurlencode($param6));
        }

        else if ($action == "reboot" && $value != "")
        {
                if ($value == "calaos_gui")
                {
                        shell_exec("killall -9 calaos_gui");
                        //$calaos->SendRequest("system reboot calaos_gui");
                }
                else if ($value == "calaosd")
                {
                        shell_exec("killall -9 calaosd");
                        //$calaos->SendRequest("system reboot calaosd");
                }
                else if ($value == "system")
                {
                        shell_exec("sync");
                        shell_exec("reboot");
                        //$calaos->SendRequest("system reboot all");
                }
        }

        else if ($action == "local_config" && opt != "" && value != "")
        {
                if ($opt == "eth0_dhcp" || $opt == "eth0_address" || $opt == "eth0_netmask" ||
                    $opt == "eth0_broadcast" || $opt == "eth0_gateway" || $opt == "eth1_address" ||
                    $opt == "eth1_netmask" || $opt == "eth1_broadcast" || $opt == "eth1_gateway" ||
                    $opt == "dns_address" || $opt == "update_url")
                {
                        setConfigOption($opt, $value);
                }
                else if ($opt == "calaos_user")
                {
                        setConfigOption($opt, $value);
                        setConfigOption("calaos_password", $param1);
                }
        }

        else if ($action == "update_fw")
        {
                $calaos->SendRequest("firmware webupdate");
        }

        else if ($action == "help" && $opt == "action")
        {
                echo getTooltip($param1, $id);
        }

        else if ($action == "delete_ssh")
        {
                shell_exec("rm -fr /mnt/ext3/dropbear");

                StopSSHDaemon();

                if (getConfigOption("ssh_enable") == "true")
                        StartSSHDaemon();
        }

        else if ($action == "ssh")
        {
                setConfigOption("ssh_enable", $value);

                if ($value == "true" && !isSSHRunning())
                        StartSSHDaemon();

                if ($value == "false" && isSSHRunning())
                        StopSSHDaemon();
        }

?>