<?php
        //auth stuff
        require_once "Utils.php";

        @session_start();

        if (isset($_POST['u']) && isset($_POST['p']))
        {
                $_SESSION['calaos_user'] = $_POST['u'];
                $_SESSION['calaos_password'] = $_POST['p'];
        }

        if (isset($_GET['u']) && isset($_GET['p']))
        {
                $_SESSION['calaos_user'] = $_GET['u'];
                $_SESSION['calaos_password'] = $_GET['p'];
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

                if ($user != $_SESSION['calaos_user'] ||
                    $pass != $_SESSION['calaos_password'])
                {
                        exit("<script>location.href = 'index.php';</script>");
                }
        }
        else
                exit("<script>location.href = 'index.php';</script>");
?>
