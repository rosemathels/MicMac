<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <?php
    //On reprend la suite du code
        $message='';
        if (empty($_POST['mail']) || empty($_POST['passe']) ) //Oublie d'un champ
        {
            $message = '<p>une erreur s\'est produite pendant votre identification.
    	Vous devez remplir tous les champs</p>
    	<p>Cliquez <a href="./connexion.php">ici</a> pour revenir</p>';
        }
        else //On check le mot de passe
        {
          $link = mysqli_connect("127.0.0.1", "root", "","micmac");
          $mail = $_POST['mail'];
          $passe = $_POST['passe'];
          $passe = sha1($passe);
          $requete = "SELECT * FROM users  WHERE mail='".$mail."'";
          if($result = mysqli_query($link, $requete)) {
            while($ligne = mysqli_fetch_assoc($result)) {
              $lg[]=$ligne;
              if ($ligne['mdp'] == md5(sha1($_POST['passe'])) and $ligne['mail'] == md5($_POST['mail'])) // Acces OK !
              {
                  $_SESSION['pseudo'] = $ligne['pseudo'];
                  $_SESSION['mdp'] = $ligne['mdp'];
                  $_SESSION['id_user'] = $data['id_user'];
                  $message = '<p>Bienvenue '.$ligne['pseudo'].',
                  vous êtes maintenant connecté!</p>
                  <p>Cliquez <a href="./index.php">ici</a>
                  pour revenir à la page d accueil</p>';
              }
              else // Acces pas OK !
              {
                  $message = '<p>Une erreur s\'est produite
                  pendant votre identification.<br /> Le mot de passe ou le pseudo
                        entré n\'est pas correcte.</p><p>Cliquez <a href="./connexion.php">ici</a>
                  pour revenir à la page précédente
                  <br /><br />Cliquez <a href="./index.php">ici</a>
                  pour revenir à la page d accueil</p>';
              }
          }



        }

        echo $message.'</div></body></html>';
        $query->CloseCursor();


    }

     ?>
  </body>
</html>
