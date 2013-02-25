<?php
        //Check user identity
        require "auth.php";

        if (isset($_FILES['firmware']))
        {
                $uploadfile = "/tmp/image.tar.bz2";

                if (move_uploaded_file($_FILES['firmware']['tmp_name'], $uploadfile))
                {
?>
<html><head>
<link rel="stylesheet" type="text/css" href="design.css" />
</head>
<body style="text-align:center;">
<textarea>true;</textarea>
</body>
</html>
<?php
                }
        }
?>
