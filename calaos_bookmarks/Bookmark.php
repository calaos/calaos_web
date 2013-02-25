<?php

include_once("config.php");

if (isset($_GET["new"]))
{
        $url = $_GET["new"];
        $title = $_GET["title"];
        $category = $_GET["category"];
        $thumb_file = $_GET["thumb_file"];

        $bk = new BookmarkList();

        $bk->addBookmark($title, $url, $category, $thumb_file);

        exit(0);
}

class Bookmark
{
        private $id;
        private $title;
        private $url;
        private $category;

        public function __construct($id, $title, $url, $category)
        {
                $this->id = & $id;
                $this->title = & $title;
                $this->url = & $url;
                $this->category = & $category;

                if (substr($this->url, 0, 4) != "http")
                        $this->url = "http://" . $this->url;
        }

        public function __destruct()
        {
        }

        public function toHtml($edit_mode = false)
        {
                global $config;

                //get thumbnail
                $thumb = $config["thumb_dir"]."/thumb_".$this->id.".png";

                if (!file_exists($thumb))
                        $thumb = "html.png";

                $html = '<li class="Bookmark">';
                $html .= '<a class="ItemContent" href="'.$this->url.'">';
                $html .= '<div class="ItemThumb"><img src="thumb.php?id='.$this->id.'" alt="thumb" height="128" /></div>';
                $html .= '<em>'.$this->title.'</em><small>'.$this->url.'</small>';
                $html .= '</a>';

                $html .= '<div class="bt" style="overflow:hidden;"><form method="GET" action="index.php">';
                $html .= '<input rel="del" href="confirm_del.php?id='.$this->id.'" name="btdel" value="Enlever le signet" type="submit">';
                $html .= '</form></div>';

                $html .= '</li>';

                return $html;
        }

}

function smartCopy($source, $dest, $folderPermission='0755',$filePermission='0644')
{
# source=file & dest=dir => copy file from source-dir to dest-dir
# source=file & dest=file / not there yet => copy file from source-dir to dest and overwrite a file there, if present

# source=dir & dest=dir => copy all content from source to dir
# source=dir & dest not there yet => copy all content from source to a, yet to be created, dest-dir
    $result=false;

    if (is_file($source)) { # $source is file
        if(is_dir($dest)) { # $dest is folder
            if ($dest[strlen($dest)-1]!='/') # add '/' if necessary
                $__dest=$dest."/";
            $__dest .= basename($source);
            }
        else { # $dest is (new) filename
            $__dest=$dest;
            }
        $result=copy($source, $__dest);
        chmod($__dest,$filePermission);
        }
    elseif(is_dir($source)) { # $source is dir
        if(!is_dir($dest)) { # dest-dir not there yet, create it
            @mkdir($dest,$folderPermission);
            chmod($dest,$folderPermission);
            }
        if ($source[strlen($source)-1]!='/') # add '/' if necessary
            $source=$source."/";
        if ($dest[strlen($dest)-1]!='/') # add '/' if necessary
            $dest=$dest."/";

        # find all elements in $source
        $return = true; # in case this dir is empty it would otherwise return false
        $dirHandle=opendir($source);
        while($file=readdir($dirHandle)) { # note that $file can also be a folder
            if($file!="." && $file!="..") { # filter starting elements and pass the rest to this function again
#                echo "$source$file ||| $dest$file<br />\n";
                $result=smartCopy($source.$file, $dest.$file, $folderPermission, $filePermission);
                }
            }
        closedir($dirHandle);
        }
    else {
        $result=false;
        }
    return $result;
}

class BookmarkList
{
        private $db;

        public function __construct()
        {
                global $config;

                if (!file_exists($config["database"]))
                {
                        //copy default bookmarks to CF
                        smartCopy("/usr/share/calaos/web/calaos_bookmarks/bookmarks.db", $config["database"]);
                        smartCopy("/usr/share/calaos/web/calaos_bookmarks/thumbs", $config["thumb_dir"]);
                }

                $this->db = @sqlite_open($config["database"], 0666, $error);

                if ($this->db === false)
                {
                        //copy default bookmarks to CF
                        smartCopy("/usr/share/calaos/web/calaos_bookmarks/bookmarks.db", $config["database"]);
                        smartCopy("/usr/share/calaos/web/calaos_bookmarks/thumbs", $config["thumb_dir"]);

                        $this->db = @sqlite_open($config["database"], 0666, $error);

                        if ($this->db === false)
                        {
                            die("Error with Database !");
                        }
                }

                //create database if non-existent
                @sqlite_query($this->db, "CREATE TABLE bookmark (id INTEGER PRIMARY KEY, url, title, category)");
        }

        public function __destruct()
        {
                @sqlite_close($this->db);
        }

        public function getCategories()
        {
                $cats = Array();

                $res = @sqlite_query($this->db, "SELECT category FROM bookmark", SQLITE_ASSOC, $error);

                if ($res === false)
                {
                        echo "Error getting categories:<br>".$error;
                        return $cats;
                }

                while ($val = sqlite_fetch_array($res))
                {
                        $cats[] = $val["category"];
                }

                return $cats;
        }

        public function getBookmarks($category, $edit_mode = false)
        {
                if ($category == "")
                        $res = @sqlite_query($this->db, "SELECT id, url, title FROM bookmark ORDER BY title", SQLITE_ASSOC, $error);
                else
                        $res = @sqlite_query($this->db, "SELECT id, url, title FROM bookmark WHERE category='".$category."' ORDER BY title", SQLITE_ASSOC, $error);

                if ($res === false)
                {
                        echo "Error getting bookmarks for category:".$category."<br>".$error;
                        return false;
                }

                $html = "";

                while ($val = sqlite_fetch_array($res))
                {
                        $bookmark = new Bookmark($val["id"], stripslashes($val["title"]), stripslashes($val["url"]), null, $category);
                        $html .= $bookmark->toHtml($edit_mode);
                }

                return $html;
        }

        public function addBookmark($title, $url, $category, $thumb_file)
        {
                global $config;

                $res = sqlite_query($this->db, "INSERT INTO bookmark(url, title, category) VALUES('".sqlite_escape_string($url)."','".sqlite_escape_string($title)."','".sqlite_escape_string($category)."')", SQLITE_ASSOC, $error);

                if ($res === false)
                {
                        echo "Error adding bookmark: ".$url."<br>".$error;
                        return false;
                }

                $res = sqlite_query($this->db, "SELECT id FROM bookmark WHERE url='".$url."'", SQLITE_ASSOC, $error);

                if ($res === false)
                {
                        echo "Error 2 adding bookmark: ".$url."<br>".$error;
                        return false;
                }

                $val = sqlite_fetch_array($res);

                @mkdir($config["thumb_dir"], 0777, true);
                @copy($thumb_file, $config["thumb_dir"]."/thumb_".$val["id"].".png");
        }

        public function deleteBookmark($id)
        {
                global $config;

                $res = @sqlite_query($this->db, "DELETE FROM bookmark WHERE id='".$id."'", SQLITE_ASSOC, $error);

                if ($res === false)
                {
                        echo "Error deleting bookmark: ".$id."<br>".$error;
                        return false;
                }

                @mkdir($config["thumb_dir"], 0777, true);
                @unlink($config["thumb_dir"]."/thumb_".$id.".png");
        }
}

?>
