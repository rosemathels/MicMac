<!-- Script pour supprimer un compte utilisateur -->

<?php
session_start();
//Connexion à la BDD
$bdd = mysqli_connect("localhost", "root", "", "micmac");
if (!$bdd) {
  //renvoie d'erreur en cas d'échec de connexion à la BD
  echo "ERROR";
  exit;
}
//Récupération de l'ID user
$id_user = $_SESSION["id_user"];
//Suppression du compte de la BD
$requete_delete = "DELETE FROM users WHERE id_user = '$id_user'";
$result_delete = mysqli_query($bdd, $requete_delete);
//Si la requête de suppression n'aboutie pas
if (!$result_delete){
  //on renvoie une erreur
  echo "ERROR";
}
else{
  echo "SUCCESS";
}
//destruction de la session de l'utilisateur
session_destroy();
//suppression des informations de la session
$_SESSION = array();
?>
