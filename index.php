<?php

	if (!file_exists(getenv("HOME") . "/.config/calaos/local_config.xml"))
	{	
		include_once "install.php";
                exit();
	}

        //auth stuff
        require_once "Utils.php";



        @session_start();

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

        $redirect = false;
        if (isset($_GET['u']) && isset($_GET['p']))
        {
                $_SESSION['calaos_user'] = $_GET['u'];
                $_SESSION['calaos_password'] = $_GET['p'];
                $redirect = true;
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

                if ($user == $_SESSION['calaos_user'] &&
                    $pass == $_SESSION['calaos_password'])
                {
                        if ($redirect)
                        {
                                header("Location: index.php");
                                exit();
                        }

                        //When one reload the main page, force a refresh of the IO list cache
                        $_SESSION['input_changed'] = "true";
                        $_SESSION['output_changed'] = "true";

                        //User auth is ok
                        include_once "main.php";
                        exit();
                }
        }

?>
<!DOCTYPE HTML>
<html>
<head>
<title>Simple Login Form</title>
<meta charset="UTF-8" />
<link rel="stylesheet" href="assets/css/reset.css" type="text/css" media="screen" />
<link rel="stylesheet" href="assets/css/layout_login.css" type="text/css" media="screen" />
<!--[if lt IE 9]>
<link rel="stylesheet" href="assets/css/ie.css" type="text/css" media="screen" />
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<body>
<form class="box login" method="post" action="index.php" name="user">
<h1>Connexion</h1>
	<fieldset class="boxBody">
	  <label>Nom d'utilisateur</label>
	  <input name="u" id="username" type="text" tabindex="1" placeholder="Adresse email" required autofocus>
	  Mot de passe</label>
	  <input name="p" id="password" type="password" placeholder="Mot de passe" tabindex="2" required>
	</fieldset>
	<footer>
	  <img alt="calaos" src="assets/img/logo_calaos.png"></img>
	  <input type="submit" class="btnLogin" value="Login" tabindex="4">
	</footer>
</form>
<footer id="main">
  <a href="http://wwww.calaos.fr">©2012 Calaos</a>
</footer>
</body>
</html>

