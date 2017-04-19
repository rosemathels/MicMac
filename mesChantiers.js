window.onload = function(){

function getInfosChantier() {

  var ajax = new XMLHttpRequest();
	var url = "mesChantiers.php";
	ajax.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {

      //On récupère les infos du chantier
      var result = this.responseText;
      console.log(result);
      var nom_chantier = result["nom_chantier"];
      var type_cam = result["type_cam"];
      var resolution = result["resolution"];
      var avancement = result["avancement"];
      var nb_etapes = result["nb_etapes"];
      var statut = result["statut"];
      var avancement_rel = (avancement/nb_etapes)*100;

    };
	}
	ajax.open("GET", url, true);
	ajax.send();

}

function afficherInfos(){

  var div_nom = document.getElementById("nom_chantier");
  div_nom.innerHTML = "Nom du chantier : "+nom_chantier;

  var div_param = document.getElementById("param");
  div_param.innerHTML = "Type de caméra : "+type_cam;

  var div_avancement = document.getElementById("avancement");
  div_avancement.innerHTML = "Avancement : "+avancement_rel;

}

}
