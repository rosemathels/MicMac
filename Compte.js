window.onload = function(){

function getInfosCompte(){

  var ajax = new XMLHttpRequest();
	var url = "Compte.php";
	ajax.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {

      var result_brut = this.responseText;
      var result = JSON.parse(result_brut);

      //Récupération des infos de l'utilisateur
      var pseudo = result[0]["pseudo"];
      var mail = result[0]["mail"];
      var id_user = result[0]["id_user"];
      var mdp = result[0]["mdp"];

      //Affichage du pseudo
      var div_pseudo = document.getElementById("pseudo");
      var p_pseudo = document.createElement("p");
      p_pseudo.innerHTML = "Bonjour "+pseudo+".";
      div_pseudo.appendChild(p_pseudo);

    }
  }

  ajax.open("GET", url, true);
	ajax.send();
}

getInfosCompte();

}
