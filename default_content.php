<?php
        //Check user identity
        require "auth.php";
?>
<div style="text-align: center;">
<?php

        echo '<img alt="calaos" src="img/bandeau'. rand(1, 4) .'.jpg" /></div>';

?>
<div class="maincontent">
<strong>Bienvenue !</strong> sur l'interface de gestion de votre maison. Utilisez le menu de gauche pour
effectuer une des actions suivantes:<br/><br/>
<div class="maincontentli">
<img style="vertical-align: middle;" src="img/home_small.png" alt="home" /> <strong>Pilotez</strong> vos équipement &eacute;lectriques dans la partie «<em>Ma Maison</em>».<br/>
<img style="vertical-align: middle;" src="img/multi_small.png" alt="multimedia" />  <strong>Visualisez</strong> vos cam&eacute;ras de surveillance dans «<em>Multimédia</em>».<br/>
<img style="vertical-align: middle;" src="img/config_small.png" alt="config" />  <strong>Configurez</strong> les r&egrave;gles et sc&eacute;narios de votre maison dans «<em>Configuration</em>».<br/>
</div>
</div>
