<?php
        //Check user identity
        require "auth.php";

        //Get config
        require_once "Utils.php";
?>
<h1 class="list_header">Journal du syst&egrave;me :</h1>

<button dojoType="Button" onclick="ShowSyslog(); return true;">
<div class="inside_button">Mettre &agrave; jour</div></button>

<textarea name="logger" id="log1" cols=80 rows=40 wrap=off readonly>
<?php
        $file = file_get_contents ('/var/log/messages');
        echo $file;
?>
</textarea>