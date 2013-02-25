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
                        //When one reload the main page, force a refresh of the IO list cache
                        $_SESSION['input_changed'] = "true";
                        $_SESSION['output_changed'] = "true";

                        //User auth is ok
                        $user_auth_ok = true;
                }
        }

        $version = getConfigOption("fw_version");
        $user = getConfigOption("calaos_user");
?>
<html>
        <head>
                <title>Calaos Home</title>
                <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
                <link rel="Stylesheet" href="WebApp/Design/Render.css" />
                <link rel="Stylesheet" href="iPhoneButtons.css" />
                <script type="text/javascript" src="WebApp/Action/Logic.js"></script>
                <script type="text/javascript" src="calaos.js"></script>

                <style>
                        .msg {
                                background-color:#080;
                                color:#fff;
                                font-size:11px;
                                padding:5px;
                                -webkit-border-radius:4px;
                                margin:8px;
                        }
                        .err {
                                background-color:#800;
                                color:#fff;
                                font-size:11px;
                                padding:5px;
                                -webkit-border-radius:4px;
                                margin:8px;
                        }
                </style>
        </head>

<body -dir="rtl"><div id="WebApp">
<div id="iHeader">
        <a href="#" id="waBackButton">Retour</a>
        <a href="#" id="waHomeButton">Accueil</a>
        <a href="#" onclick="return WA.HideBar()"><span id="waHeadTitle">Calaos Home</span></a>
</div>

<div id="iGroup">
        <div id="iLoader">Chargement en cours...</div>

        <div class="iLayer" id="waMain" title="Home">
<?php
        if ($user_auth_ok == true)
        {
?>
                <a href="login.php?logout=1" rel="action" class="iButton iBAction" id="logout">Quitter</a>

                <div class="iMenu">
                        <h3>Votre Maison</h3>
                        <ul class="iArrow">
                                <li><a href="menu_home.php#_Home" rev="async"><img src="img/home_icon.png" width="56" height="56" /><em>Ma Maison</em><small>G&eacute;rer sa maison</small></a></li>
                                <li><a href="camera.php#_Camera" rev="async" ><img src="img/media_icon.png" width="56" height="56" /><em>Vid&eacute;osurveillance</em><small>Visualiser ses cam&eacute;ras</small></a></li>
                                <li><a href="music.php#_Music" rev="async"><img src="img/media_icon.png" width="56" height="56" /><em>Ma Musique</em><small>G&eacute;rez votre musique</small></a></li>
                                <li><a href="#_About"><img src="img/about_icon.png" width="56" height="56" /><em>A Propos</em><small>A propos de Calaos Home</small></a></li>
                        </ul>
                </div>
<?php
        }
        else
        {
?>
                <a href="#" rel="action" class="iButton iBAction" onclick="return document.getElementById('form_login').submit();">Login</a>
                <form id="form_login" action="login.php" method="POST">

                <div class="iPanel">
                        <?php if ($login_error == true) { ?>
                        <div id="form-error"><div class="err">Nom d'utilisateur ou mot de passe incorrect !</div></div>
                        <?php } ?>
                        <fieldset>
                                <legend>Connection :</legend>
                                <ul>
                                        <li><input type="text" autocapitalize="off" autocorrect="off" name="u" placeholder="Nom d'utilisateur" /></li>
                                        <li><input type="password" name="p" placeholder="Mot de passe" /></li>
                                </ul>
                        </fieldset>
                </div>

                </form>
<?php
        }
?>
        </div>

        <div class="iLayer" id="waMedia" title="Multimedia">
                <div class="iMenu">
                        <h3>Ecoutez et visualisez</h3>
                        <ul class="iArrow">
                                <li><a href="music.php#_Music" rev="async"><img src="img/media_icon.png" width="56" height="56" /><em>Ma Musique</em><small>G&eacute;rez votre musique</small></a></li>
                                <li><a href="camera.php#_Camera" rev="async" ><img src="img/media_icon.png" width="56" height="56" /><em>Vid&eacute;osurveillance</em><small>Visualiser ses cam&eacute;ras</small></a></li>
                        </ul>
                </div>
        </div>

        <div class="iLayer" id="waAbout" title="A propos">
                <div class="iBlock">
                        <h1>Calaos Home, une solution domotique.</h1>
                        <p>Firmware revision: <?php echo $version; ?></p>
                        <p>Plus d'informations sur le site <a href="http://www.calaos.fr">www.calaos.fr</a></p>
                </div>
        </div>
</div>

</div></body>
</html>

