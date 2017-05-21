//script qui interroge la BD et récupère les informations des chantiers
//pour les afficher dans la page html mes chantiers avec les éléments DOM

window.onload = function(){

function getInfosChantier() {

  var ajax = new XMLHttpRequest();

  //le lien vers lequel on va envoyer la requête Ajax
	var url = "mesChantiers.php";
	ajax.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {

      //On récupère le résultat sous format JSON
      var result_brut = this.responseText;
      //et on le parse
      var result = JSON.parse(result_brut);

      var nb_chantiers = result.length;

      //On parcourt tous les chantiers trouvés :
      for(i = 0; i < nb_chantiers; i++){

        //Récupération infos du chantier
        var nom_chantier = result[i]["nom_chantier"];
        var type_cam = result[i]["type_cam"];
        var resolution = result[i]["resolution"];
        var date = result[i]["date"];
        var avancement = result[i]["avancement"];
        var nb_etapes = result[i]["nb_etapes"];
        var statut = result[i]["statut"];
        var nom_complet = result[i]["nom_complet"];
        var reso_sortie = result[i]["sortie"];
        var garder_calcul = result[i]["garder_calcul"];
        var avancement_rel = (Number(avancement)/Number(nb_etapes))*100;

        //Affichage dans le DOM

        //On regarde si le chantier est en cours ou terminé pour choisir où on affiche les infos
        if(statut == "termine"){
          var div_statut = document.getElementById("termine");

          //On indique le nom, les paramètres et l'avancement de chaque chantier
          var texte_nom = nom_chantier;
          var texte_type = type_cam;
          var texte_date = date;
          var texte_avancement = avancement;

          var textnode_nom = document.createTextNode(texte_nom);
          var textnode_type = document.createTextNode(texte_type);
          var textnode_date = document.createTextNode(texte_date);
          var textnode_avancement = document.createTextNode(avancement_rel+"%");

          // ajout d'une ligne à la fin de la table
          var newRow   = div_statut.getElementsByTagName('tbody')[0].insertRow();

          // insertion des cellules
          var newCell1  = newRow.insertCell();
          var newCell2  = newRow.insertCell();
          var newCell3  = newRow.insertCell();
          var newCell4  = newRow.insertCell();

          newCell1.appendChild(textnode_nom);
          newCell2.appendChild(textnode_type);
          newCell3.appendChild(textnode_date);

          //Création de la liste des liens
          var liste_liens = document.createElement("ul");
          liste_liens.style.padding = "0px";
          newCell4.appendChild(liste_liens);

          if(garder_calcul == 1){
            //Lien contenant le dossier complet
            var li1 = document.createElement("li");
            var lien_all = document.createElement("a");
            var texte_lien_all = "uploads/"+nom_complet+".zip";
            lien_all.setAttribute("href", texte_lien_all);
            lien_all.setAttribute("download", nom_chantier+".zip");
            lien_all.innerHTML = "Télécharger le dossier complet (.zip)";
            li1.appendChild(lien_all);
            li1.style.listStyle = "none";
            liste_liens.appendChild(li1);
          }
          else{
			//Lien contenant le dossier "simplifié"
			var li1 = document.createElement("li");
			var lien_simple = document.createElement("a");
			var texte_lien_simple = "uploads/"+nom_complet+".zip";
			lien_simple.setAttribute("href", texte_lien_simple);
			lien_simple.setAttribute("download", nom_chantier+"_basics.zip");
			lien_simple.innerHTML = "Télécharger le nuage de points + l'orthophoto + le fichier de géoréférencement + les résidus (.zip)";
			li1.appendChild(lien_simple);
			li1.style.listStyle = "none";
			liste_liens.appendChild(li1);
		  }

          //Lien contenant le nuage de points
          var li2 = document.createElement("li");
          var lien_nuage = document.createElement("a");
          var option;
          var texte_lien_nuage = "uploads/"+nom_complet+"_out/Nuage3D.ply";
          lien_nuage.setAttribute("href", texte_lien_nuage);
          lien_nuage.setAttribute("download", nom_chantier+"_nuage.ply");
          lien_nuage.innerHTML = "Télécharger le nuage de points (.ply)";
          li2.appendChild(lien_nuage);
          li2.style.listStyle = "none";
          liste_liens.appendChild(li2);

          //Lien contenant l'orthophoto
          var li3 = document.createElement("li");
          var lien_ortho = document.createElement("a");
          var texte_lien_ortho = "uploads/"+nom_complet+"_out/Orthophotomosaic.tif";
          lien_ortho.setAttribute("href", texte_lien_ortho);
          lien_ortho.setAttribute("download", nom_chantier+"_ortho.tif");
          lien_ortho.innerHTML = "Télécharger l'orthophoto (.tif)";
          li3.appendChild(lien_ortho);
          li3.style.listStyle = "none";
          liste_liens.appendChild(li3);

          //Lien contenant l'orthophoto
          var li3bis = document.createElement("li");
          var lien_georef = document.createElement("a");
          var texte_lien_georef = "uploads/"+nom_complet+"_out/Orthophotomosaic.tfw";
          lien_georef.setAttribute("href", texte_lien_georef);
          lien_georef.setAttribute("download", nom_chantier+"_ortho.tfw");
          lien_georef.innerHTML = "Télécharger le fichier de géoréférencement de l'orthophoto (.tfw)";
          li3bis.appendChild(lien_georef);
          li3bis.style.listStyle = "none";
          liste_liens.appendChild(li3bis);

          //Lien contenant les résidus
          var li4 = document.createElement("li");
          var lien_residus = document.createElement("a");
          var texte_lien_residus = "uploads/"+nom_complet+"_out/Residus_TiePoints.xml";
          lien_residus.setAttribute("href", texte_lien_residus);
          lien_residus.setAttribute("download", nom_chantier+"_residus_liaison.xml");
          lien_residus.innerHTML = "Télécharger le fichier de résidus des points de liaisons (.xml)";
          li4.appendChild(lien_residus);
          li4.style.listStyle = "none";
          liste_liens.appendChild(li4);

          //Lien contenant les résidus
          var li5 = document.createElement("li");
          var lien_residus_terrain = document.createElement("a");
          var texte_lien_residus_terrain = "uploads/"+nom_complet+"_out/Residus_Terrain.xml";
          lien_residus_terrain.setAttribute("href", texte_lien_residus_terrain);
          lien_residus_terrain.setAttribute("download", nom_chantier+"_residus_terrain.xml");
          lien_residus_terrain.innerHTML = "Télécharger le fichier de résidus terrain (.xml)";
          li5.appendChild(lien_residus_terrain);
          li5.style.listStyle = "none";
          liste_liens.appendChild(li5);

        }

        else{
          var div_statut = document.getElementById("en_cours");

          //On indique le nom, les paramètres et l'avancement de chaque chantier
          var texte_nom = nom_chantier;
          var texte_type = type_cam;
          var texte_date = date;
          if(statut == "archivage"){
			var texte_avancement = "Archivage en cours...";
		  }
		  else{
			var texte_avancement = avancement_rel+"%";
		  }

          var textnode_nom = document.createTextNode(texte_nom);
          var textnode_type = document.createTextNode(texte_type);
          var textnode_date = document.createTextNode(texte_date);
          var textnode_avancement = document.createTextNode(texte_avancement);

          // ajout d'une ligne à la fin de la table
          var newRow   = div_statut.getElementsByTagName('tbody')[0].insertRow();

          // insertion des cellules
          var newCell1  = newRow.insertCell();
          var newCell2  = newRow.insertCell();
          var newCell3  = newRow.insertCell();
          var newCell4  = newRow.insertCell();

          newCell1.appendChild(textnode_nom);
          newCell2.appendChild(textnode_type);
          newCell3.appendChild(textnode_date);
          newCell4.appendChild(textnode_avancement);
        }
      };
    }
	}
	ajax.open("GET", url, true);
  //envoie de la requête Ajax
	ajax.send();

}
getInfosChantier();

}
