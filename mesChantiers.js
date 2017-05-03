window.onload = function(){

function getInfosChantier() {

  var ajax = new XMLHttpRequest();
	var url = "mesChantiers.php";
	ajax.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {

      var result_brut = this.responseText;
      var result = JSON.parse(result_brut);

      var nb_chantiers = result.length;
      console.log(nb_chantiers);

      //On parcourt tous les chantiers trouvés :
      for(i = 0; i < nb_chantiers; i++){

        //Récupération infos du chantier
        var nom_chantier = result[i]["nom_chantier"];
        var type_cam = result[i]["type_cam"];
        var resolution = result[i]["resolution"];
        var avancement = result[i]["avancement"];
        var nb_etapes = result[i]["nb_etapes"];
        var statut = result[i]["statut"];
        var avancement_rel = (Number(avancement)/Number(nb_etapes))*100;

        //Affichage dans le DOM

        //On regarde si le chantier est en cours ou terminé pour choisir où on affiche les infos
        if(statut == "termine"){
          var div_statut = document.getElementById("termine");
        }
        else{
          var div_statut = document.getElementById("en_cours");
        }

        var p_chantier = document.createElement("p");

        var texte_nom = "Nom du chantier : "+nom_chantier;
        var texte_type = "Type de caméra : "+type_cam;
        var texte_resolution = "Résolution : "+resolution;
        var texte_avancement = "Avancement : "+avancement_rel+"%";

        var textnode_nom = document.createTextNode(texte_nom);
        var textnode_type = document.createTextNode(texte_type);
        var textnode_resolution = document.createTextNode(texte_resolution);
        var textnode_avancement = document.createTextNode(texte_avancement);

        p_chantier.appendChild(textnode_nom);
        p_chantier.appendChild(textnode_type);
        p_chantier.appendChild(textnode_resolution);
        p_chantier.appendChild(textnode_avancement);

        //Si le chantier est terminé, on propose le lien de téléchargement

        div_statut.appendChild(p_chantier);

      };

      }

	}
	ajax.open("GET", url, true);
	ajax.send();

}

getInfosChantier();

}
