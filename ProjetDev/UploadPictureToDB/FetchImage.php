<?php
$host = 'localhost';
$user = 'test';
$pass = 'mZnbqJK9jJmXwqOd';

mysql_connect($host, $user, $pass);

mysql_select_db('micmac');

$select_path="select * from image_table";

$var=mysql_query($select_path);

while($row=mysql_fetch_array($var))
{
 $image_name=$row["imagename"];
 $image_path=$row["imagepath"];
 echo "img src=".$image_path."/".$image_name." width=100 height=100";
}
?>
