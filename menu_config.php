<?php
        //Check user identity
        require "auth.php";
?>
<table style="text-align: center; width: 100%;" border="0"
 cellpadding="2" cellspacing="2">
  <tbody>
    <tr>
      <td style="text-align: center; width: 25%;">
      <a href="javascript:SaveConfig();"><img style="width: 48px; height: 48px;" alt="io" src="img/save.png"><br>
Sauvegarde</a></td>
      <td style="text-align: center; width: 25%;">
      <a href="javascript:RebootMenu();"><img style="width: 48px; height: 48px;" alt="io" src="img/reboot.png"><br>
Reboot</a></td>
      <td style="text-align: center; width: 25%;"><a href="javascript:NetworkConfig();"><img style="width: 48px; height: 48px;" alt="network" src="img/network.png"><br>
Edition des param&egrave;tres r&eacute;seau</a></td>
      <td style="text-align: center; width: 25%;"><a href="javascript:ShowSyslog();"><img style="width: 48px; height: 48px;" alt="log" src="img/log.png"><br>
Afficher les logs du syst&egrave;me</a></td>
    </tr>
    <tr>
      <td style="text-align: center; width: 25%;">
        <a href="javascript:SSHConfig();"><img style="width: 48px; height: 48px;" alt="io" src="img/network.png"><br>
        SSH</a>
      </td>
      <td style="text-align: center; width: 25%;"></td>
      <td style="text-align: center; width: 25%;"></td>
      <td style="text-align: center; width: 25%;"></td>
    </tr>
    <tr>
      <td style="text-align: center; width: 25%;"></td>
      <td style="text-align: center; width: 25%;"></td>
      <td style="text-align: center; width: 25%;"></td>
      <td style="text-align: center; width: 25%;"></td>
    </tr>
  </tbody>
</table>
