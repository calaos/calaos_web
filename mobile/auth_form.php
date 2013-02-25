<?php

        include "header.php";
?>
<img src="img/logo_calaos.png" alt="calaos" />
<h1 class="title">Calaos: Connexion à votre maison <em>mobile</em></h1>
<?php if ($login_error == true) { ?>
<div id="login_error">Nom d'utilisateur ou mot de passe incorrect !</div>
<?php } ?>
<form method="post" action="login.php" name="login">
Nom d'utilisateur:<br />
<input name="u" /><br />
Mot de passe:<br />
<input name="p" type="password" /><br /><br />
<input type='submit' value='Se connecter' name='connect'/><br />
</form>
<?php
        include "footer.php";
?>