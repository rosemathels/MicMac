<?php
  session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>User Page</title>
    <link rel="stylesheet" href="UserPage.css">
  </head>
  <body>
    <div>
      <form method= "post" action="UploadPicture.php" enctype="multipart/form-data">
        <label>Nom du chantier :</label>
        <input type="text" name="nom_chantier"><br>
        <label>Type de caméra :</label>
        <select name="type_camera">
          <option value="classique" selected>Caméra classique</option>
          <option value="fisheye">FishEye</option>
        </select>
       <input type="file" name="myimage" id="myimage">
       <input type="submit" name="submit_image" value="Upload"><br>
     </form>
    </div>
  </body>
</html>
