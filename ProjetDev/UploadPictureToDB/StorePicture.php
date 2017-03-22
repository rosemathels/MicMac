<?php
$host = 'localhost';
$user = 'test';
$pass = 'mZnbqJK9jJmXwqOd';

mysql_connect($host, $user, $pass);

mysql_select_db('micmac');

$upload_image=$_FILES["myimage"][ "name" ];

$folder="uploads/";

move_uploaded_file($_FILES["myimage"]["tmp_name"], "$folder".$_FILES["myimage"]["name"]);

$insert_path="INSERT INTO image_table VALUES('$folder','$upload_image')";

$var=mysql_query($insert_path);
?>
