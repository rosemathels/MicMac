<?php
$link = mysqli_connect("127.0.0.1", "root", "","micmac");
if(isset($_POST['email']) && isset($_POST['pass'])){
  $mail = mysqli_real_escape_string($link,htmlspecialchars($_POST['email']));
  $passe = $_POST['pass'];
  $passe = sha1($passe);
  $requete = "SELECT * FROM users  WHERE email='$mail'";
  if($result = mysqli_query($link, $requete)) {

    while($ligne = mysqli_fetch_assoc($result)) {
      $id_user = $ligne["id_user"];
      $pseudo = $ligne["pseudo"];
      $mdp = $ligne["mdp"];
      $email = $ligne["email"];
      if($mdp == $passe && $email == $mail){
        session_start();
        $_SESSION['pseudo'] = $pseudo;
        $_SESSION['mdp'] = $mdp;
        $_SESSION['id_user'] = $id_user;
        header('location: CreerChantier.php');
        exit();
      }
      else {
        echo "Veuillez vérifier le mail ou le mot de passe que vous avez rentré!";
      }
    }

  }
}
else{
  echo "Echec de l'authentification!";
}

mysqli_close($link);
?>
