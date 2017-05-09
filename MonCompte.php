<?php

session_start();

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Mon Compte</title>
    <script src = "Compte.js"></script>
  </head>

  <body>

    <div id="pseudo"></div>

    <div id="mail"></div>

    <div id="mdp"></div>

    <div id="suppr">
      <a href="supprCompte.php" onclick="return confirm('Êtes-vous sûr(e) de vouloir supprimer votre compte ? Cette opération n'est pas réversible.');">Supprimer mon compte</a>

      <?php
      //Récupération du code d'erreur passé en GET (s'il existe)
      if(isset($_GET["error"])){

        if($_GET["error"] == "delete"){
          echo "Votre compte n'a pas pu être supprimé. Réessayez plus tard.";
        }
      }
      ?>

    </div>

  </body>
</html>
