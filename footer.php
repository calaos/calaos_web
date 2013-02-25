<?php
        require_once "Utils.php";

        $version = getConfigOption("fw_version");
        $user = getConfigOption("calaos_user");
?>
</div>
<div class="footer">
<table width="100%"><tr>
<td>&#169;2008 Calaos</td>
<td align="center">Connecté: <em><?php echo $user; ?></em></td>
<td align="right">Firmware revision: <?php echo $version; ?></td>
</tr></table>
</div>
</td>
<td width="15" valign="top" background="img/right_shadow.png" style="background-repeat: repeat-y;"/>
</table>
</body>
</html>