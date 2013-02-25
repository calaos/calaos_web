<?php
        //Check user identity
        require_once "../Utils.php";

        //auth stuff
        @session_start();
        $user_auth_ok = false;

        if (isset($_GET['logout']) && $_GET['logout'] == "1")
        {
                session_unset();
                session_destroy();
                $_SESSION = array();
        }

        if (isset($_POST['u']) && isset($_POST['p']))
        {
                $_SESSION['calaos_user'] = $_POST['u'];
                $_SESSION['calaos_password'] = $_POST['p'];
        }

        if (isset($_SESSION['calaos_user']) &&
            isset($_SESSION['calaos_password']))
        {
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

                if ($_SESSION['calaos_user'] == $user &&
                    $_SESSION['calaos_password'] == $pass)
                {
                        //User auth is ok
                        $user_auth_ok = true;
                }
        }

        if ($user_auth_ok == false)
        {
                //reload
                header("Location: index.php?login_error=1");
        }
        else
        {
                //reload
                header("Location: index.php");
        }
?>
