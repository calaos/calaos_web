<?php
        //auth stuff
        require_once "Utils.php";

        //detect special browser here
        if (isIphone())
        {
                header("Location: iphone/index.php");
                exit();
        }

        //detect special browser here
        if (isMobileDevice())
        {
                header("Location: mobile/index.php");
                exit();
        }

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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>:: Calaos - Control your Home</title>
<?php
        if (isset($debug))
        {
?>
<script type="text/javascript">
        var djConfig = {isDebug: true };
</script>
<?php
        }
?>
<script type="text/javascript" src="dojo.js"></script>
<script language="JavaScript" type="text/javascript">
        dojo.require("dojo.widget.*");
        dojo.require("dojo.widget.FloatingPane");
        dojo.require("dojo.widget.ResizeHandle");
        dojo.require("dojo.widget.ComboBox");
        dojo.require("dojo.widget.ContentPane");
        dojo.require("dojo.widget.Button");
        dojo.require("dojo.widget.Spinner");
        dojo.require("dojo.widget.DropdownTimePicker");
</script>
<script type="text/javascript" src="calaos.js"></script>
<?php
        if ($_SESSION['calaos_user'] != "" ||
            $_SESSION['calaos_password'] != "")
        {
?>
<script type="text/javascript">
        setTimeout('ShowStatus("Authentification &eacute;chou&eacute;e ! Nom d\'utilisateur ou mot de passe incorrect.", false)', 1000);
</script>
<?php
        }
?>
<link rel="stylesheet" type="text/css" href="design.css" />
</head>
<body background="img/bg.png">
<table width="750" cellspacing="0" cellpadding="0" border="0" align="center"><tr>
<td width="15" valign="top" background="img/left_shadow.png" style="background-repeat: repeat-y;"/></td>
<td>
<div class="main">
<div class="logo">
<a href="http://www.calaos.fr"><img alt="calaos" src="img/calaos.new.png" /></a>
</div>
<div class="clear"></div>

<div class="toolbar">
<table width="100%">
<tr>
<td><span id="status"></span></td>
<td align="right"><em>Veuillez vous identifier</em></td>
</tr>
</table>
</div>

<table width="100%" border="0">
<tr>
<td valign="top">


<table class="contentpaneopen">
        <tr>
                <td class="contentheading">Votre Maison</td>
        </tr>
        <tr>

                <td class="contentpaneopen">
                        <p>
                        Bienvenue sur la page de connexion à votre maison.<br /> 
                        Entrez votre nom d'utilisateur pour tenter une connexion sécurisée. 
                        </p>
                </td>
        </tr>
</table>

<br />

<form method="post" action="index.php" name="user">
<table align="center" style="text-align: center; width: 300px; border: 1px solid #7f9bc0; padding-top: 10px; padding-bottom: 10px;" border="0" cellpadding="2" cellspacing="2" class="contentpaneopen">
        <tr> 
                <td> 
                        <p style="text-align: center;"><img alt="cle" src="img/cle.png"/></p>
                </td> 
                <td>
                                <b>Nom d'utilisateur :</b><br /> 
                                <input name="u" class="inputboxhome" size="40"/><br />
                                <b>Mot de passe :</b><br />
                                <input name="p" type="password" class="inputboxhome" size="40"/><br /><br />

                                <div style="text-align: right; padding-right: 25px;">
                                <input class="submit_button" name="submit" type="submit" value="Valider" />
                                </div>
                </td>
        </tr>
</table>
</form>

<table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2" class="contentpaneopen">
        <tr> 
                <td> 
                        <p style="text-align: center;"><a href="mobile"><img alt="pda" src="img/pda.png"/></a></p>
                </td> 
                <td> 
                       <b>Accès depuis un téléphone portable:</b><br />

                        Vous vous connectez depuis un appareil mobile?<br />Utilisez l'adresse <a style="color: #0099FF;" href="mobile">mobile</a> depuis le navigateur de votre téléphone portable.
                </td> 
        </tr> 
</table>

</td></tr></table>
<?php
        $version = getConfigOption("fw_version");
        $user = getConfigOption("calaos_user");
?>
</div>
<div class="footer">
<table width="100%"><tr>
<td>&#169;2006 Calaos</td>
<td align="right">Firmware revision: <?php echo $version; ?></td>
</tr></table>
</div>
</td>
<td width="15" valign="top" background="img/right_shadow.png" style="background-repeat: repeat-y;"/>
</table>
</body>
</html>
