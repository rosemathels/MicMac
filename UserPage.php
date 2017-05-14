<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Mes chantiers en cours</title>
    <link rel="stylesheet" href="UserPage.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src = "mesChantiers.js"></script>
  </head>
  <body>

    <div class="menu">
     <div class="container-fluid">
         <div>
         <ul class="nav navbar-nav navbar-right">
            <li><a href="#" ><span class="glyphicon glyphicon-user"></span> Compte</a>
                <ul class="dropdown">
                    <li><a href="MonCompte.php">Supprimer compte</a></li>
                    <li><a href="ChangerMdp.php">Changer mot de passe</a></li>
                </ul>
            </li>
            <li><a href="Logout.php"><span class="glyphicon glyphicon-log-in"></span> Déconnexion</a></li>
         </ul>
         </div>
      </div>
    </div>

    <div class="container">
      <div class="table-responsive">
        <h2>Chantiers en cours</h2>
        <table class="table" id="en_cours">
          <thead>
            <tr>
              <th>chantier</th>
              <th>camera</th>
              <th>résolution</th>
              <th>avancement</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>

      <h2>Chantiers terminés</h2>
      <div class="table-responsive">
        <table class="table" id="termine">
          <thead>
            <tr>
              <th>chantier</th>
              <th>camera</th>
              <th>résolution</th>
              <th>avancement</th>
              <th>lien</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
      <input class="bouton" type="button" value="Créer un nouveau chantier" OnClick="javascript:location.href='CreerChantier.php'"/>
    </div>
  </body>
</html>
<!-- <?php

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

 ?> -->
