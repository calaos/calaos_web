<?php
        //Check user identity
        require "auth.php";

        $debug = @$_GET['debug'];

        include "header.php";

        echo '<td width="100%" valign="top"><div dojoType="ContentPane" id="content" executeScripts="true" parseContent="true" cacheContent="false">';
        include "default_content.php";
        echo '</div></td></tr></table>';

        if (isset($debug))
                echo '<div dojoType="DebugConsole"'.
                     'title="Debug Window"'.
                     'style="width: 400px; position: absolute; height: 200px; left: 50px; top: 550px;"></div>';

        include "footer.php";
?>