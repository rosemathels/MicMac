<?php

function setMicMacTable($id_chantier){
  //fonction qui insère les instructions MicMac dans la table instructions_micmac dans la BDD

  $param = "SELECT type_cam, resolution, format_img from chantier WHERE id_chantier = ".$id_chantier;

  $liste_instructions = setMicMacInstructions();
  $ordre = 1;

  for($instr in $liste_instructions){
    $requete_instructions = "INSERT INTO instructions_micmac VALUES (".$id_chantier.",".$instr.",".$ordre.")";
    $result_instructions = pg_query($bdd, $requete_instructions);
    if (!$result_instructions) {
      echo "Erreur : les instructions MicMac n'ont pas pu être insérées dans la base de données.";
      exit;
    }
    $ordre++;
  };

}

function setMicMacInstructions($param){
  //fonction qui génère la liste ordonnée des instructions MicMac à effectuer en fonction des paramètres du

  //Récupération des paramètres
  $type_cam = param[0]
  $resolution = param[1];
  $format_img = param[2];

  //Détermination du modèle de distorsion en fonction du type de caméra
  if(type_cam == "classique"){
    $modele_distorsion = "RadialStd";
  }
  else if(type_cam == "fisheye"){
    $modele_distorsion = "FishEyeEqui"
  }

  //Initialisation de la liste des instructions
  $liste_instructions = [];

  //Extraction des coordonnées GPS dans les exif
  //Sortie => GpsCoordinatesFromExif.txt
  $XifGps2Txt = 'mm3d XifGps2Txt ".*'.$format_img.'" Sys=GeoC';
  array_push($liste_instructions, $XifGps2Txt);

  //Conversion des coordonnées en L93, en dossier d'orientation MicMac pour faire la bascule ensuite, et en fichier xml pour les points de liaison
  //Sortie => FileGPS.xml
  $OriConvert = 'mm3d OriConvert "#F=N_X_Y_Z" GpsCoordinatesFromExif.txt GPS_L93 NameCple=FileGPS.xml ChSys=GeoC@Lambert93';
  array_push($liste_instructions, $OriConvert);

  //Recherche des points de liaisons
  //Sortie => Homol => PastisImagename => fichiers binaires(.dat) contenant les points de liaison
  $Tapioca = 'mm3d Tapioca FileGPS.xml '.$resolution;
  array_push($liste_instructions, $Tapioca);

  //Mise en place relative et calibration
  //Sortie => Rel
  $Tapas = 'mm3d Tapas '.$modele_distorsion.' ".*'.$format_img.'" Out=Rel';
  array_push($liste_instructions, $Tapas);

  //Basculement rigide à partir de la mise en place relative pour s'appuyer sur les somemts GPS
  //Sortie =>
  $CenterBascule = 'mm3d CenterBascule ".*'.$format_img.'" Rel GPS_L93 Bascule_L93';
  array_push($liste_instructions, $CenterBascule);

  //si GPS précis sur la phase...
  //$Campari = 'mm3d Campari...'
  //array_push($liste_instructions, $Campari);

  //Reconstruction 3D - éventuellement donner le choix à l'utilisateur entre MicMac et les deux autres...
  //Sortie => ?
  $C3DC = 'mm3d C3DC MicMac ".*'.$format_img.'" Bascule_L93';
  array_push($liste_instructions, $C3DC);

  //Conversion en grille 2.5D (MNT, carte de profondeur) + orthorectification des images individuellement
  //Sortie => ?
  $Pims2Mnt = 'mm3d Pims2Mnt MicMac DoOrtho=1';
  array_push($liste_instructions, $Pims2Mnt);

  //Assemblage des orthos individuelles - éventuellement donner le choix à l'utilisateur entre Deq=0, RadoimEgal=0, .....
  //Sortie => ?
  $Tawny = 'mm3d Tawny PIMs-ORTHO/ Deq=0';
  array_push($liste_instructions, $Tawny);

  return $liste_instructions;

}

?>
