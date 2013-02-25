<?php
        require "../auth.php";
        require_once "../Utils.php";
        include "header.php";
?>
<h1 class="title">Calaos Home <em>Mobile</em></h1>
<div class="Menu">
<ul id="main_menu">
        <li class="ArrowMore"><a class="ItemContent" href="home.php">
                <img src="../iphone/img/home_icon.png" width="56" height="56" />
                <em>Ma Maison</em><small>Gérer sa maison</small>
        </a></li>
        <li class="ArrowMore"><a class="ItemContent" href="camera.php">
                <img src="../iphone/img/media_icon.png" width="56" height="56" />
                <em>Vidéosurveillance</em><small>Visualiser ses caméras</small>
        </a></li>
        <li class="ArrowMore"><a class="ItemContent" href="music.php">
                <img src="../iphone/img/media_icon.png" width="56" height="56" />
                <em>Musique</em><small>Gérer sa musique</small>
        </a></li>
        <li><a class="ItemContent" href="login.php?logout=1">
                <img src="../iphone/img/about_icon.png" width="56" height="56" />
                <em>Déconnexion</em><small>Se déconnecter de sa maison</small>
        </a></li>
</ul>
</div>
<?php
        include "footer.php";
?>