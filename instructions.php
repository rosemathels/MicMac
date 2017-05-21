<!-- Ce script php permet de remplir la BD avec les instructions en fonction des paramètres choisis par l'utilisateur -->
<?php

function setMicMacTable($id_chantier,$conn){
  //fonction qui insère les instructions MicMac dans la table instructions_micmac dans la BDD

  //récupérer les paramètres rentrés par l'utilisateur
  $requete_param = "SELECT type_cam, resolution, format_img, sortie from chantiers WHERE id_chantier =".$id_chantier;
  $param = mysqli_query($conn, $requete_param);

  //on fait appel à une fonction qu'on a déclaré plus loin dans ce script
  $liste_instructions = setMicMacInstructions($param);
  $ordre = 1;

  //remplissage de la BD
  foreach($liste_instructions as $instr){
    $requete_instructions = "INSERT INTO instructions VALUES (NULL,'$id_chantier','$instr','$ordre')";
    $result_instructions = mysqli_query($conn, $requete_instructions);
    if (!$result_instructions) {
      echo "Erreur : les instructions MicMac n'ont pas pu être insérées dans la base de données.";
      exit;
    }
    $ordre++;
  };

  $nb_instr = sizeof($liste_instructions);

  return $nb_instr;

}

function setMicMacInstructions($param){
  //fonction qui génère la liste ordonnée des instructions MicMac à effectuer en fonction des paramètres du

  //Récupération des paramètres
  foreach ($param as $para) {
    $type_cam = $para["type_cam"];
    $resolution = $para["resolution"];
    $format_img = $para["format_img"];
    $reso_sortie = $para["sortie"];
  }

  //Détermination du modèle de distorsion en fonction du type de la caméra
  if($type_cam == "classique"){
    $modele_distorsion = "RadialStd";
  }
  else if($type_cam == "fisheye"){
    $modele_distorsion = "FishEyeEqui";
  }

  //Initialisation de la liste des instructions
  $liste_instructions = [];

  //Extraction des coordonnées GPS dans les exif
  //Sortie => GpsCoordinatesFromExif.txt
  $XifGps2Txt = 'XifGps2Txt ".*'.$format_img.'" Sys=GeoC';
  array_push($liste_instructions, $XifGps2Txt);

  //Conversion des coordonnées en L93, en dossier d'orientation MicMac pour faire la bascule ensuite, et en fichier xml pour les points de liaison
  //Sortie => FileGPS.xml
  $OriConvert = 'OriConvert "#F=N_X_Y_Z" GpsCoordinatesFromExif.txt GPS_L93 NameCple=FileGPS.xml ChSys=GeoC@Lambert93';
  array_push($liste_instructions, $OriConvert);

  //Recherche des points de liaisons
  //Sortie => Homol => PastisImagename => fichiers binaires(.dat) contenant les points de liaison
  $resolution /= 3;
  $Tapioca = 'Tapioca File FileGPS.xml '.$resolution;
  array_push($liste_instructions, $Tapioca);

  //Mise en place relative et calibration
  //Sortie => Rel
  $Tapas = 'Tapas '.$modele_distorsion.' ".*'.$format_img.'" Out=Rel';
  array_push($liste_instructions, $Tapas);

  //Basculement rigide à partir de la mise en place relative pour s'appuyer sur les somemts GPS
  //Sortie =>
  $CenterBascule = 'CenterBascule ".*'.$format_img.'" Rel GPS_L93 Bascule_L93';
  array_push($liste_instructions, $CenterBascule);

  //Reconstruction 3D - éventuellement donner le choix à l'utilisateur entre MicMac et les deux autres...
  //Sortie => ?
  if($reso_sortie == "haute"){
    $option = "BigMac";
  }
  else if($reso_sortie == "moyenne"){
    $option = "MicMac";
  }
  else if($reso_sortie == "faible"){
    $option = "QuickMac";
  }
  $C3DC = 'C3DC '.$option.' ".*'.$format_img.'" Bascule_L93 Out=Nuage3D.ply';
  array_push($liste_instructions, $C3DC);

  //Conversion en grille 2.5D (MNT, carte de profondeur) + orthorectification des images individuellement
  //Sortie => ?
  $Pims2Mnt = 'Pims2Mnt '.$option.' DoOrtho=1';
  array_push($liste_instructions, $Pims2Mnt);

  //Assemblage des orthos individuelles - éventuellement donner le choix à l'utilisateur entre Deq=0, RadoimEgal=0, .....
  //Sortie => ?
  $Tawny = 'Tawny PIMs-ORTHO/ DEq=0';
  array_push($liste_instructions, $Tawny);

  return $liste_instructions;

}

?>
