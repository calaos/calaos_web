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
        dojo.require("dojo.io.IframeIO");
</script>
<script type="text/javascript" src="calaos.js"></script>
<link rel="stylesheet" type="text/css" href="design.css" />
</head>
<body background="img/bg.png">
<table width="750" cellspacing="0" cellpadding="0" border="0" align="center"><tr>
<td width="15" valign="top" background="img/left_shadow.png" style="background-repeat: repeat-y;"/></td>
<td>
<div class="main">
<table width="100%"><tr>
<td align="left">
<div class="logo">
<a href="http://www.calaos.fr"><img alt="calaos" src="img/calaos.new.png" /></a>
</div>
</td>
<td align="right" valign="bottom">
<a href="#" onclick="ShowStatus('Linux Everywhere !', true); return true;"><img alt="tux" src="img/tux.png" />
</td>
</tr></table>
<div class="clear"></div>

<div class="toolbar">
<table width="100%">
<tr>
<td><span id="status"></span></td>
<td align="right"><img style="vertical-align: middle;" src="img/exit.gif" alt="exit" /> <a href="index.php?logout=1">Se d&eacute;connecter</a></td>
</tr>
</table>
</div>

<table width="100%" border="0">
<tr>
<td valign="top">
<div id="menu">
<a href="javascript:MenuHome();"><img alt="home" src="img/home.png" /></a>
<a href="javascript:MenuMultimedia();"><img alt="multimedia" src="img/multi.png" /></a>
<a href="javascript:MenuConfig();"><img alt="config" src="img/config.png" /></a>
<div id="loading"><img src="img/loading_squares.gif" alt="loading" /></div>
</div>
</td>