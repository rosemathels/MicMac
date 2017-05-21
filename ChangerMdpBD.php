<!-- ce script php permet de récupérer les données de l'utilisateur
qui se trouvent dans la BD pour gérer les erreurs de mot de passe -->

<?php
session_start();
//on récupère la session de l'utilisateur
$id_user = $_SESSION["id_user"];
//on se connecte à la base de données qui contient les informations sur les utilisateurs de la page
$link = mysqli_connect("localhost", "root", "","micmac");
//Si la connexion à la BD échoue, on affiche une erreur
if(!$link){
  echo "FAILED";
}
//Si les champs sont bien remplis
if(isset($_POST['old_pass']) && isset($_POST['new_pass']) && isset($_POST['new_pass2'])){
  //on récupère l'ancien mot de passe de l'uitilisateur
  $rqt = "SELECT mdp FROM users WHERE id_user = '$id_user'";
  $result = mysqli_query($link, $rqt);
  //si le résultat de la requête sql n'est pas vide
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
          //on compare l'ancien mot de passe avec le mot de passe que l'utilisateur a rentré
          if(sha1($_POST['old_pass']) == $row['mdp']){
            //si les nouveaux mots de passe que l'utilisateur a rentré sont équivalents
            if ($_POST['new_pass']==$_POST['new_pass2']) {
              //alors on récupère le nouveau mot de passe
              $passe = $_POST['new_pass2'];
              $passe = sha1($passe);
              //et on met à jour ce dernier dans la table utilisateurs de la BD
              $requete = "UPDATE users SET mdp = '$passe' WHERE id_user = '$id_user'";
              if(mysqli_query($link, $requete)) {
                  //si la requête de mise à jour s'effectue bien, on renvoie SUCCESS
                  echo "SUCCESS";
              }
              //sinon, benh on renvoie échec
              else {
                  echo "FAILED";
              }
            }
            //si les deux mots de passe ne correspondent pas on renvoie une erreur
           else{
              echo "ERROR1";
            }
          }
        //sinon si l'ancien mot de passe que l'utilisateur a rentré n'existe pas dans la BD, on renvoie une 2ème erreur!
       else{
          echo "ERROR2";
        }
    }
  }
}
//fermeture de la connexion à la BD
mysqli_close($link);
?>
