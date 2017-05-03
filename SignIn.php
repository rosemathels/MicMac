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
        header('location: mesChantiers.html');
        //echo '<script type="text/javascript">window.location.href="/ProjetDev/UserPage/UserPage.php";</script>';
        exit();
        //die();
      }
      else {
        echo "Wrong credentials!";
      }
    }
  }
}
else{
  echo "Failed to log in!";
}

mysqli_close($link);
?>
