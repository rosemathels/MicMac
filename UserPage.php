<!-- Page html qui contient les informations sur les chantiers en cours et les chantiers terminés -->
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
  </head>
  <body>

   <div class="menu">
    <div class="container-fluid">
        <div>
        <ul class="nav navbar-nav navbar-right">
           <li><a href="#" ><span class="glyphicon glyphicon-user"></span> Compte</a>
                <ul class="dropdown">
                    <li><a id="delete" href="supprCompte.php">Supprimer compte</a></li>
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
        <h3>Chantiers en cours</h3>
        <table class="table" id="en_cours">
          <thead>
            <tr>
              <th>Chantier</th>
              <th>Camera</th>
              <th>Date</th>
              <th>Avancement</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>

      <h3>Chantiers terminés</h3>
      <div class="table-responsive">
        <table class="table" id="termine">
          <thead>
            <tr>
              <th>Chantier</th>
              <th>Camera</th>
              <th>Date</th>
              <th>Liens</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
     <input class="bouton" type="button" value="Créer un nouveau chantier" OnClick="javascript:location.href='CreerChantier.php'"/>
    </div>


  <script src = "mesChantiers.js"></script>
  <script src = "supprCompte.js"></script>

  </body>
</html>
