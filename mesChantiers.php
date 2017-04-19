<?php

//Connexion à la BDD
$bdd = mysqli_connect("localhost", "root", "", "micmac");
if (!$bdd) {
  echo "Erreur de connexion à la BDD.";
  exit;
}

//Récupération de l'ID de l'user
$id_user = $_SESSION["id_user"];

//Récupération des informations du chantier
$requete = "SELECT * FROM chantier WHERE id = ".$id_user;
$result = mysqli_query($bdd, $requete);
if (!$result) {
  echo "Erreur : les informations n'ont pas pu être récupérées.";
  exit;
}
$result = mysqli_fetch_assoc($result);

echo $result;

 ?>
