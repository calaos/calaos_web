<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Calaos - Home Page</title>
<link rel="stylesheet" type="text/css" href="design.css" />
<link rel="stylesheet" type="text/css" href="squeezebox/assets/SqueezeBox.css" />
<script src="mootools.js" type="text/javascript"></script>
<script src="squeezebox/SqueezeBox.js" type="text/javascript"></script>
</head><body>
<?php

require_once ("Bookmark.php");

$bk = new BookmarkList();

if (isset($_GET["btyes"]) && isset($_GET["id"]))
{
        $bk->deleteBookmark($_GET["id"]);
}

?>
<div id="top">

<div id="google">
<img alt="google" src="google.png" width="256"/>
<form method="GET" action="http://www.google.fr/search">
<input name="q" size="41" maxlength="2048" value="" title="Recherche google" type="text">
<input name="btnG" value="Recherche Google" type="submit">
</form>
</div>

<div class="header">
        <div class="headerlinks_select"><a href="index.php">Favoris</a></div>
<!--         <div class="headerlinks"><a href="history.php">Historique</a></div> -->
        <div class="headerlinks" style="float:right;" id="edit_button"><a href="#">Edition</a></div>
</div>
</div>

<div id="contentwrap">
<div id="content">

<!--
<div id="Categories">
<h1>Catégories</h1>
<ul>
<li><a href="index.php">Tous</a></li>
<?php
/*
        $cats = $bk->getCategories();

        for ($i = 0;$i < count($cats);$i++)
        {
                echo '<li><a href="index.php?category='.urlencode($cats[$i]).'">'.$cats[$i].'</a></li>';
        }
*/
?>
</ul>
</div>
-->

<ul class="List" id="bookmarks_list">
<?php

echo $bk->getBookmarks($category);

?>
</ul>
</div>
</div>


<script type="text/javascript">

var edit_mode = false;

window.addEvent('domready', function()
{

        $('bookmarks_list').getElements('div.bt').each(function(item)
        {
                item.setStyles({'height': '0'});
        });

        $('edit_button').addEvent('click', function()
        {
                if (!edit_mode)
                {
                        $('bookmarks_list').getElements('div.bt').each(function(item)
                        {
                                item.morph({'height': '40px'});
                        });

                        edit_mode = true;

                        $('edit_button').getElement('a').innerHTML = "Arrêter l'édition";
                }
                else
                {
                        $('bookmarks_list').getElements('div.bt').each(function(item)
                        {
                                item.morph({'height': '0'});
                        });

                        edit_mode = false;

                        $('edit_button').getElement('a').innerHTML = "Edition";
                }

                return false;
        });

        SqueezeBox.assign($$('input[rel=del]'), { size: {x: 300, y: 135} });

});

</script>
</body>
</html>