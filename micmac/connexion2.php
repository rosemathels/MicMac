<?php
session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
  </form>
  <?php
  $link = mysqli_connect("127.0.0.1", "root", "","micmac");
  if(isset($_POST['email']) && isset($_POST['passe'])){

  $mail = mysqli_real_escape_string($link,htmlspecialchars($_POST['email']));
  $passe = $_POST['passe'];
  $passe = sha1($passe);

  //requete
  $requete = "SELECT * FROM users  WHERE mail='$mail'";
  if($result = mysqli_query($link, $requete)) {

    while($ligne = mysqli_fetch_assoc($result)) {
      $id_user = $ligne["id_user"];
      $pseudo = $ligne["pseudo"];
      $mdp = $ligne["mdp"];
      $email = $ligne["mail"];

        // Set session variables
      $_SESSION["pseudo"] = $pseudo;
      $_SESSION["email"] = $email;
      $_SESSION["mdp"] = $mdp;
      print_r($_SESSION);


  }
}
}
else {
  echo " pas de donnees";
}

    ?>
  </body>
</html>
