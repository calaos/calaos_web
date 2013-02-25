<?php
        //Check user identity
        require "auth.php";
?>
<h1 class="list_header">Commandes IR :</h1>

<table style="text-align: left; width: 90%;" border="0" cellpadding="2" cellspacing="2">
<tbody>
        <tr>
                <td style="vertical-align: top;text-align: center; width: 40%;">
                        <div class="list_scenario">
                        <a href="#" class="sub_scenario">Kenwood</a>
                        <a href="#" class="sub_scenario">Sony</a>
                        <a href="#" class="sub_scenario">Denon</a>
                        <a href="#" class="sub_scenario">Telecommande a gauche du canapé</a>
                        </div>
                </td>

                <td style="vertical-align: top;text-align: center; width: 100%;">
                <div dojoType="ContentPane" style="border: 1px solid #eee; padding: 10px;">
                <h1 class="list_header">Kenwood</h1>
                <table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2">
                <tbody>
                        <tr>
                        <td><button dojoType="Button"><div class="inside_button">Off</div></button></td>
                        <td><button dojoType="Button"><div class="inside_button">Prog +</div></button></td>
                        <td><button dojoType="Button"><div class="inside_button">Prog -</div></button></td>
                        <td><button dojoType="Button"><div class="inside_button">Tuner</div></button></td>
                        </tr><tr>
                        <td><button dojoType="Button"><div class="inside_button">CD</div></button></td>
                        <td><button dojoType="Button"><div class="inside_button">Volume -</div></button></td>
                        <td><button dojoType="Button"><div class="inside_button">Volume +</div></button></td>
                        </tr>
                </tbody>
                </table>
                </div>
                </td>
        </tr>
  </tbody>
</table>

<table style="text-align: left;" border="0" cellpadding="2" cellspacing="2">
<tbody>
        <tr>
                <td style="text-align: center;"><button dojoType="Button"><div class="inside_button">Nouvelle Tel.</div></button></td>
                <td style="text-align: center;"><button dojoType="Button"><div class="inside_button">Supprimer</div></button></td>
                <td style="text-align: center;"><button dojoType="Button"><div class="inside_button">Apprendre Code</div></button></td>
                <td style="text-align: center;"><button dojoType="Button"><div class="inside_button">Supprimer Code</div></button></td>
        </tr>
  </tbody>
</table>
