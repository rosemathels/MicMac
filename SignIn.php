<?php
$link = mysqli_connect("127.0.0.1", "root", "","micmac");

if(!$link){
  echo "FAILED";
  exit;
}

if(isset($_POST['email']) && isset($_POST['pass'])){
  $mail = $_POST['email'];
  $passe = $_POST['pass'];
  $passe = sha1($passe);
  $requete = "SELECT * FROM users  WHERE email='$mail'";
  $results = mysqli_query($link, $requete);

  if(count($results)>=1) {

    while($ligne = mysqli_fetch_assoc($results)) {
      $id_user = $ligne["id_user"];
      $pseudo = $ligne["pseudo"];
      $mdp = $ligne["mdp"];
      $email = $ligne["email"];
      if($mdp == $passe && $email == $mail){
        session_start();
        $_SESSION['pseudo'] = $pseudo;
        $_SESSION['mdp'] = $mdp;
        $_SESSION['id_user'] = $id_user;
        echo "SUCCESS";
      }
      else {
        echo "ERROR";
      }
    }
  }
  else{
    echo "ERROR";
  }
}
else{
  echo "ERROR";
}

mysqli_close($link);
?>
