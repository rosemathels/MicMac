<?php
$link = mysqli_connect("127.0.0.1", "root", "","micmac");
if (!$link){
  echo "FAILED";
}

if(isset($_POST['passe']) && isset($_POST['passe2']) && isset($_POST['pseudo']) && isset($_POST['email'])){
  $passe = mysqli_real_escape_string($link,htmlspecialchars($_POST['passe']));
  $passe2 = mysqli_real_escape_string($link,htmlspecialchars($_POST['passe2']));
  if($passe == $passe2)
  {
    $pseudo = mysqli_real_escape_string($link,htmlspecialchars($_POST['pseudo']));
    $email = mysqli_real_escape_string($link,htmlspecialchars($_POST['email']));
    $passe = sha1($passe);
    $rqt = "INSERT INTO users (id_user, pseudo, email, mdp) VALUES(NULL, '$pseudo', '$email', '$passe')";
    $result = mysqli_query($link,$rqt);
  }

  else
  {
    echo "ERROR";
  }
}

mysqli_close($link);
?>
