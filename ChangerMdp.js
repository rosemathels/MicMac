//Ce script js permet d'effectuer une requête ajax vers le fichier ChangerMdpBD.php
//et de modifier la page html selon les réponses récupérées


//Ajout d'un écouteur d'événements sur le formulaire ChangePass qui se trouve dans le fichier ChangerMdp.php
var form = document.querySelector('#ChangePass form');
form.addEventListener('submit', function (evt) {
  //empêcher la page html de se rafraîchir lorsqu'on clique sur le bouton
  evt.preventDefault();

  //récupérer le contenu des champs dans le formulaire
  var old_pass = form.elements['old_pass'].value;
  var new_pass = form.elements['new_pass'].value;
  var new_pass2 = form.elements['new_pass2'].value;

  //nouvelle requête Ajax
  var ajax = new XMLHttpRequest();

  //le lien que qu'on souhaite interroger
  var url = "ChangerMdpBD.php";

  ajax.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {

      //si la réponse Ajax qu'on a récupéré est un type d'erreur1, on affiche un message d'erreur à l'utilisateur
      if(ajax.responseText == 'ERROR1'){
        document.getElementById("error").innerHTML = "Veuillez vérifier le nouveau mot de passe!";
        document.getElementById("error").setAttribute("style", "color: red;");
      }
      //La même chose ici..
      else if (ajax.responseText == 'ERROR2') {
        document.getElementById("error").innerHTML = "Veuillez vérifier l'ancien mot de passe!";
        document.getElementById("error").setAttribute("style", "color: red;");
      }
      //si la réponse est echec(problème de BD) on affiche un autre message d'erreur...
      else if (ajax.responseText == 'FAILED') {
        document.getElementById("error").innerHTML = "Veuillez réessayer plus tard...";
        document.getElementById("error").setAttribute("style", "color: orange;");
      }
      //sinon on affiche un message de succès
      else {
        document.getElementById("error").innerHTML = "Modifications enregistrées avec succès!";
        document.getElementById("error").setAttribute("style", "color: #4682B4;");
      }

    }
  }
  ajax.open("POST", url, true);
  ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
  //envoie de la requête Ajax
  ajax.send('old_pass=' + old_pass + '&new_pass=' + new_pass + '&new_pass2=' + new_pass2);
})
