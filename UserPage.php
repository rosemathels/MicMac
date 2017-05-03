<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Mes chantiers en cours</title>
    <script src = "mesChantiers.js"></script>
  </head>
  <body>

    <?php

    //Récupération du code d'erreur passé en GET (s'il existe)
    if(isset($_GET["error"])){

      if($_GET["error"] == "conn"){
        echo "Erreur de connexion à la base de données. Réessayez plus tard.";
      }

      else if($_GET["error"] == "notimage"){
        echo "Votre chantier contient des fichiers qui ne sont pas des images. Vérifiez le contenu, puis réessayez.";
      }

      else if($_GET["error"] == "double"){
        echo "Votre chantier contient des images en double. Vérifiez le contenu, puis réessayez.";
      }

      else if($_GET["error"] == "size"){
        echo "Votre chantier est trop volumineux. Attendez qu'on crée un site plus performant, puis réessayez.";
      }

      else if($_GET["error"] == "type"){
        echo "Attention, seules les extensions JPG, JPEG, GIF et PNG sont autorisées.";
      }

      else if($_GET["error"] == "upload"){
        echo "Le chargement des images a échoué. Réessayez plus tard.";
      }

      else if($_GET["error"] == "insert"){
        echo "Les données du chantier n'ont pas pu être insérées dans la base de données. Réessayez plus tard.";
      }

      else if($_GET["error"] == "none"){
        echo "Chantier créé avec succès.";
      }

    }

     ?>

    <a href="CreerChantier.php" title="créer un chantier">Créer un nouveau chantier</a>

    <h3>Chantiers en cours</h3>

    <div id="en_cours"></div>

    <h3>Chantiers terminés</h3>

    <div id="termine"></div>

  </body>
</html>
