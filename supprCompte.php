<?php

session_start();

//Connexion à la BDD
$bdd = mysqli_connect("localhost", "root", "", "micmac");
if (!$bdd) {
  echo "ERROR";
  exit;
}

//Récupération de l'ID user
$id_user = $_SESSION["id_user"];

//Suppression du compte
$requete_delete = "DELETE FROM users WHERE id_user = '$id_user'";
$result_delete = mysqli_query($bdd, $requete_delete);
if (!$result_delete){
  echo "ERROR";
}
else{
  echo "SUCCESS";
}

session_destroy();
$_SESSION = array();

?>
