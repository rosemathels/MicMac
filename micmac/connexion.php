<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>connexion</title>
    <?php
    session_start();

     ?>
  </head>
  <body>
    <form method="post" action="connexion2.php">
    <label>Adresse e-mail: <input type="text" name="email" id="email"/></label><br/>
    <label>Mot de passe: <input type="text" name="passe" id="passe"/></label><br/>
    <input type="submit" value="Ok"/>

  </body>
</html>
