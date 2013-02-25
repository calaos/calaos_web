<?php

if (!isset($_GET["id"]))
{
        echo '<h3>Erreur !</h3>';
        echo '<p>Ce signet n\'existe pas !</p>';
        echo '<form method="GET" action="index.php"><input name="btno" value="Fermer" type="submit"></form>';
        
        exit(0);
}

$id = $_GET["id"];

?>
<h3>Confirmation.</h3>
<p>
Etes-vous sûr de vouloir supprimer ce signet?
</p>
<form method="GET" action="index.php">
<input name="id" value="<?php echo $id; ?>" type="hidden">
<input name="btyes" value="Oui" type="submit">
</form>
<form method="GET" action="index.php">
<input name="btno" value="Non" type="submit">
</form>
