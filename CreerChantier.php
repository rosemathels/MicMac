<?php
  session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>User Page</title>

    <link rel="stylesheet" href="CreerChantier.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  </head>
  <body>

      <div class="menu">
       <div class="container-fluid">
   		     <div>
       		 <ul class="nav navbar-nav navbar-right">
       				<li><a href="#" ><span class="glyphicon glyphicon-user"></span> Compte</a></li>
       				<li><a href="Logout.php"><span class="glyphicon glyphicon-log-in"></span> Déconnexion</a></li>
       		 </ul>
       		 </div>
   	    </div>
      </div>

      <div class="container">
        <h2>Créer chantier</h2>
          <form class="form-horizontal" method= "post" action="UploadPicture2.php" enctype="multipart/form-data">
            <div class="form-group">
              <label class="control-label col-sm-2">Nom du chantier :</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="chantier" placeholder="Entrer nom du chantier" name="nom_chantier">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2">Type de caméra :</label>
              <div class="col-sm-10">
                <select class="form-control" id="camera" name="type_camera">
                  <option value="classique" selected>Caméra classique</option>
                  <option value="fisheye">FishEye</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2">Résolution :</label>
              <div class="col-sm-10">
                <select class="form-control" id="resolution" name="resolution">
                  <option value="haute" >Haute résolution</option>
                  <option value="moyenne" selected>Résolution moyenne</option>
                  <option value="faible">Faible résolution</option>
                </select>
              </div>
            </div>
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <input type="file" name="myimage[]" id="myimage" multiple>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-default" name="submit_image" value="Upload">Submit</button>
            </div>
          </div>
         </form>
      </div>

  </body>
</html>
