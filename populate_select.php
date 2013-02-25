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

        function get_io_params($type, $id, $param)
        {
                global $calaos;
                $res = explode(" ", $calaos->SendRequest("$type $id params?"));
                for ($i = 1;$i < count($res);$i++)
                {
                        list($key, $value) = explode(":", urldecode($res[$i]), 2);
                        if ($key == $param) return $value;
                }
        }

        function populate($_type, $_varid="")
        {
                global $calaos;

//                 if (isset($_SESSION['input_changed']) && $_SESSION['input_changed'] != "true" && $_type == "input")
//                 {
//                         echo $_SESSION['input_cache'];
// 
//                         return;
//                 }
// 
//                 if (isset($_SESSION['output_changed']) && $_SESSION['output_changed'] != "true" && $_type == "output")
//                 {
//                         echo $_SESSION['output_cache'];
// 
//                         return;
//                 }

                $_SESSION[$_type.'_changed'] = "false";
                $_SESSION[$_type.'_cache'] = "";

                //get the number of rooms
                $res = explode(" ", $calaos->SendRequest("home ?"));
                for ($i = 1;$i < count($res);$i++)
                {
                        list($room, $count) = explode(":", urldecode($res[$i]), 2);

                        $res2 = explode(" ", $calaos->SendRequest("home get ".$room));
                        list($str, $countr) = explode(":", urldecode($res2[1]), 2);
                        if ($countr <= 0) continue;

                        for ($j = 2;$j < count($res2);$j++)
                        {
                                list($id, $opt, $value) = explode(":", urldecode($res2[$j]), 3);

                                if ($opt == "name")
                                        $name = $value;

                                if ($name != "")
                                {
                                        $_SESSION[$_type.'_cache'] .= '<optgroup label="'.$name.'">';
                                        $name = "";

                                        $res3 = explode(" ", $calaos->SendRequest("room $room get $id"));
                                        for ($l = 2;$l < count($res3);$l++)
                                        {
                                                list($io, $_id) = explode(":", urldecode($res3[$l]), 2);
                                                if ($io == $_type)
                                                {
                                                        if ($_varid == $_id)
                                                                $selected = " selected";
                                                        else
                                                                $selected = "";

                                                        $_SESSION[$_type.'_cache'] .= "<option value=\"".$_id."\"$selected>".get_io($_type, $_id, "name");
                                                        $_var = get_io_params($_type, $_id, "var");
                                                        if ($_var != "")
                                                                $_SESSION[$_type.'_cache'] .= " <em>(".$_var.")</em>";

                                                        $_SESSION[$_type.'_cache'] .= "</option>";
                                                }
                                        }

                                        $_SESSION[$_type.'_cache'] .= '</optgroup>';
                                }
                        }
                }

                echo $_SESSION[$_type.'_cache'];
        }


?>