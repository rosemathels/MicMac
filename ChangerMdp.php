<?php
session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Changer Mot de</title>
    <link rel="stylesheet" href="ChangerMdp.css">
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
            <li><a href="MainPage.html"><span class="glyphicon glyphicon-log-in"></span> Acceuil</a></li>
         </ul>
         </div>
      </div>
    </div>
    <div id="ChangePass" class="container">
      <h2>Changer mot de passe</h2>
        <form class="form-horizontal" method= "post" action="ChangerMdpBD.php" enctype="multipart/form-data">
          <div class="form-group">
            <label class="control-label col-sm-2">Ancien mot de passe :</label>
            <div class="col-sm-10">
              <input type="password" class="form-control" name="old_pass">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2">Nouveau mot de passe :</label>
            <div class="col-sm-10">
              <input type="password" class="form-control" name="new_pass">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2">Resaisir mot de passe :</label>
            <div class="col-sm-10">
              <input type="password" class="form-control" name="new_pass2">
            </div>
          </div>
          <div class="col-sm-offset-2 col-sm-10" id="error"></div>
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <input class="bouton" type="submit" value="Valider"/>
            </div>
          </div>
       </form>
    </div>
    <script type="text/javascript" src="ChangerMdp.js"></script>
  </body>
</html>
