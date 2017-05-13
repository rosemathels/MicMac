window.onload = function(){

function getInfosChantier() {

  var ajax = new XMLHttpRequest();
	var url = "mesChantiers.php";
	ajax.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {

      var result_brut = this.responseText;
      var result = JSON.parse(result_brut);

      var nb_chantiers = result.length;

      //On parcourt tous les chantiers trouvés :
      for(i = 0; i < nb_chantiers; i++){

        //Récupération infos du chantier
        var nom_chantier = result[i]["nom_chantier"];
        var type_cam = result[i]["type_cam"];
        var resolution = result[i]["resolution"];
        var avancement = result[i]["avancement"];
        var nb_etapes = result[i]["nb_etapes"];
        var statut = result[i]["statut"];
        var nom_complet = result[i]["nom_complet"];
        var reso_sortie = result[i]["sortie"];
        var avancement_rel = (Number(avancement)/Number(nb_etapes))*100;

        //Affichage dans le DOM

        //On regarde si le chantier est en cours ou terminé pour choisir où on affiche les infos
        if(statut == "termine"){
          var div_statut = document.getElementById("termine");

          //On indique le nom, les paramètres et l'avancement de chaque chantier
          var texte_nom = nom_chantier;
          var texte_type = type_cam;
          var texte_resolution = resolution;
          var texte_avancement = avancement;

          var textnode_nom = document.createTextNode(texte_nom);
          var textnode_type = document.createTextNode(texte_type);
          var textnode_resolution = document.createTextNode(texte_resolution);
          var textnode_avancement = document.createTextNode(avancement_rel+"%");

          // ajout d'une ligne à la fin de la table
          var newRow   = div_statut.getElementsByTagName('tbody')[0].insertRow();

          // insertion des cellules
          var newCell1  = newRow.insertCell();
          var newCell2  = newRow.insertCell();
          var newCell3  = newRow.insertCell();
          var newCell4  = newRow.insertCell();
          var newCell5  = newRow.insertCell();

          newCell1.appendChild(textnode_nom);
          newCell2.appendChild(textnode_type);
          newCell3.appendChild(textnode_resolution);
          newCell4.appendChild(textnode_avancement);

          var lien_all = document.createElement("a");
          var texte_lien_all = "uploads/"+nom_complet+".zip";
          lien_all.setAttribute("href", texte_lien_all);
          lien_all.setAttribute("download", nom_complet+".zip");
          lien_all.innerHTML = "Télécharger le dossier complet";
          newCell5.appendChild(lien_all);

          /*var lien_nuage = document.createElement("a");
          var option;
          if(reso_sortie == "haute"){
            option = "BigMac";
          }
          else if(reso_sortie == "moyenne"){
            $option = "MicMac";
          }
          else if(reso_sortie == "faible"){
            $option = "QuickMac";
          }
          var texte_lien_nuage = "uploads/"+nom_complet+"C3DC_"+option+".ply";
          lien_nuage.setAttribute("href", texte_lien_nuage);
          lien_nuage.setAttribute("download", nom_complet+"C3DC_"+option+".ply");
          lien_nuage.innerHTML = "Télécharger le nuage de points";
          newCell5.appendChild(lien_nuage);*/

        }
        else{
          var div_statut = document.getElementById("en_cours");

          //On indique le nom, les paramètres et l'avancement de chaque chantier
          var texte_nom = nom_chantier;
          var texte_type = type_cam;
          var texte_resolution = resolution;
          var texte_avancement = avancement_rel+"%";

          var textnode_nom = document.createTextNode(texte_nom);
          var textnode_type = document.createTextNode(texte_type);
          var textnode_resolution = document.createTextNode(texte_resolution);
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
          newCell3.appendChild(textnode_resolution);
          newCell4.appendChild(textnode_avancement);
        }
      };
    }
	}
	ajax.open("GET", url, true);
	ajax.send();

}

getInfosChantier();

}
