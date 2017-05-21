//Script pour supprimer un compte utilisateur

//On ajoute un écouteur d'évenments sur le bouton Supprimer compte qui se trouve dans le menu Compte
var a = document.getElementById('delete');
a.addEventListener("click",
        function (event) {
            event.preventDefault();
            //Afficher un message de confirmation
            if (confirm('Etes-vous sûre?')) {
              //Requête Ajax
              var ajax = new XMLHttpRequest();

              //Lien vers lequel on va envoyer la requête Ajax
              var url = "supprCompte.php";

              ajax.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {

                  //Si on récupère une réponse de succès
                  if(ajax.responseText == "SUCCESS"){
                    //On redirige l'utilisateur vers la page d'accueil
                    window.location = "MainPage.html";
                  }
                  //Sinon
                  else{
                    //on affiche un message d'erreur
                    window.alert("Veuillez réessayer plus tard!")
                  }


                }
              }
              ajax.open("POST", url, true);
              ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
              //envoie de la requêt Ajax
              ajax.send();
            }
},false);
