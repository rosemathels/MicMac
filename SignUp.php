<!-- Script pour créer un compte utilisateur -->

<?php
//Connexionà la BD
$link = mysqli_connect("127.0.0.1", "root", "","micmac");
if (!$link){
  //renvoie d'erreur en cas d'echoue de la connexion à la BD
  echo "FAILED";
}
//Si les champs sont bien remplis
if(isset($_POST['passe']) && isset($_POST['passe2']) && isset($_POST['pseudo']) && isset($_POST['email'])){
  //on récupère leurs valeurs
  $passe = mysqli_real_escape_string($link,htmlspecialchars($_POST['passe']));
  $passe2 = mysqli_real_escape_string($link,htmlspecialchars($_POST['passe2']));
  //Si les deux mot de passe rentrés par l'utilisateur sont équivalents
  if($passe == $passe2){
      $pseudo = mysqli_real_escape_string($link,htmlspecialchars($_POST['pseudo']));
      $email = mysqli_real_escape_string($link,htmlspecialchars($_POST['email']));
      $passe = sha1($passe);
      //on remplie la BD
      $rqt = "INSERT INTO users (id_user, pseudo, email, mdp) VALUES(NULL, '$pseudo', '$email', '$passe')";
      $result = mysqli_query($link,$rqt);
      //on renvoie succès
      echo "SUCCESS";
    }
  //sinon si les deux mot de passe ne correspondent pas on renvoie une erreur
  else{
      echo "ERROR2";
    }
  }
//Si les champs sont vides on renvoie encore une erreur
else{
  echo "ERROR";
}
mysqli_close($link);
?>
