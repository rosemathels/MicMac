<?php

//Extraction des coordonnées GPS dans les exif
//Sortie => GpsCoordinatesFromExif.txt
$XifGps2Txt = 'mm3d XifGps2Txt ".*JPG" Sys=GeoC'

//Conversion des coordonnées en L93, en dossier d'orientation MicMac pour faire la bascule ensuite, et en fichier xml pour les points de liaison
//Sortie => FileGPS.xml
$OriConvert = 'mm3d OriConvert'

//Recherche des points de liaisons
//Sortie => RadialStd ?
$Tapioca = 'mm3d Tapioca'

//Mise en place relative et calibration
//Sortie => Rel
$Tapas = 'mm3d Tapas'

//Basculement rigide à partir de la mise en place relative pour s'appuyer sur les somemts GPS
//Sortie =>
$CenterBascule = 'mm3d CenterBascule'

//si GPS précis sur la phase...
//$Campari = 'mm3d Campari...'

//Reconstruction 3D - éventuellement donner le choix à l'utilisateur entre MicMac et les deux autres...
//Sortie => ?
$C3DC = 'mm3d C3DC MicMac'

//Conversion en grille 2.5D (MNT, carte de profondeur) + orthorectification des images individuellement
//Sortie => ?
$Pims2Mnt = 'mm3d Pims2Mnt MicMac DoOrtho=1'

//Assemblage des orthos individuelles - éventuellement donner le choix à l'utilisateur entre Deq=0, RadoimEgal=0, .....
//Sortie => ?
$Tawny = 'mm3d Tawny PIMs-ORTHO/ Deq=0'

?>
