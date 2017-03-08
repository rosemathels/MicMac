<?php

//Récupération des données du formulaire
$nom_chantier = pg_escape_string($_POST["nom_chantier"]);
$type_cam = pg_escape_string($_POST["type_camera"]);

//Récupération données de session
$date = 0;
$username = $_SESSION["username"];

//Récupération données images
include(UploadPicture.php);

//Connexion à la BDD
$bdd = pg_connect("host=localhost port=5432 dbname=micmac user=postgres password=postgres");
if (!$bdd) {
  echo "Erreur de connexion à la BDD.";
  exit;
}

//Insertions des infos du chantier dans la BDD
$requete = "INSERT INTO chantier ('',".$nom_chantier.",".$date.",".$username.",".$type_cam.",".$resolution.",".$format_img.", 0, 'en_attente',".$nb_photos.")";
$result = pg_query($bdd, $requete);
if (!$result) {
  echo "Erreur : les informations n'ont pas pu être insérées dans la base de données.";
  exit;
}

//Insertions des instructions MicMac dans la BDD


?>
