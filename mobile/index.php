<?php
        require_once "../Utils.php";

        //auth stuff
        @session_start();

        $user_auth_ok = false;
        $login_error = false;

        if (isset($_GET['login_error']))
                $login_error = true;

        if (isset($_GET['logout']) && $_GET['logout'] == "1")
        {
                session_unset();
                session_destroy();
                $_SESSION = array();
        }

        if(isset($_SERVER['PHP_AUTH_USER']))
        {
                $_SESSION['calaos_user'] = $_SERVER['PHP_AUTH_USER'];
                $_SESSION['calaos_password'] = $_SERVER['PHP_AUTH_PW'];
        }
        elseif (isset($_POST['u']) && isset($_POST['p']))
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
                        //When one reload the main page, force a refresh of the IO list cache
                        $_SESSION['input_changed'] = "true";
                        $_SESSION['output_changed'] = "true";

                        //User auth is ok
                        $user_auth_ok = true;
                }
        }

        $version = getConfigOption("fw_version");
        $user = getConfigOption("calaos_user");

        if ($user_auth_ok == true)
        {
                include 'menu.php';
        }
        else
        {
                include 'auth_form.php';
        }
?>
